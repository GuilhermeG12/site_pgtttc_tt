const express = require("express"),
  passport = require("passport"),
  session = require("express-session"),
  GoogleStrategy = require("passport-google-oauth20").Strategy,
  axios = require("axios"); // Biblioteca para fazer requisições HTTP

const app = express();

// Configuração do EJS
app.set("view engine", "ejs");
app.set("views", __dirname + "/views");

const GOOGLE_CLIENT_ID = "540318204162-7snfdg2n2t81ikdoogbrbqfeujt3r9fr.apps.googleusercontent.com";
const GOOGLE_CLIENT_SECRET = "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";
const GOOGLE_CALLBACK_URL = "http://localhost:3000/auth/google/callback";

app.use(session({ secret: "chave-secreta", resave: false, saveUninitialized: true }));
app.use(passport.initialize());
app.use(passport.session()));

passport.use(new GoogleStrategy({
  clientID: GOOGLE_CLIENT_ID,
  clientSecret: GOOGLE_CLIENT_SECRET,
  callbackURL: GOOGLE_CALLBACK_URL,
}, (accessToken, refreshToken, profile, done) => {
  // Armazenar o accessToken para usá-lo depois nas requisições à API
  profile.accessToken = accessToken;
  return done(null, profile);
}));

passport.serializeUser((user, done) => done(null, user));
passport.deserializeUser((obj, done) => done(null, obj));

// Rota que redireciona diretamente para o login do Google
app.get("/", (req, res) => res.redirect("/auth/google"));

app.get("/auth/google", passport.authenticate("google", { scope: ["profile", "email", "https://www.googleapis.com/auth/tasks"] }));
app.get("/auth/google/callback", passport.authenticate("google", { failureRedirect: "/", successRedirect: "/dashboard" }));

// Rota do Dashboard onde as tarefas do Google são exibidas
app.get("/dashboard", async (req, res) => {
  if (!req.isAuthenticated()) {
    return res.redirect("/");
  }

  try {
    const accessToken = req.user.accessToken;

    // Requisição para obter as tarefas do Google usando o accessToken
    const response = await axios.get("https://tasks.googleapis.com/tasks/v1/lists/@default/tasks", {
      headers: {
        Authorization: `Bearer ${accessToken}`,
      },
    });

    const tasks = response.data.items || [];
    const taskList = await Promise.all(tasks.map(async task => {
      // Requisição para obter as subtarefas de cada tarefa
      const subtasksResponse = await axios.get(`https://tasks.googleapis.com/tasks/v1/lists/@default/tasks/${task.id}/subtasks`, {
        headers: {
          Authorization: `Bearer ${accessToken}`,
        },
      });

      const subtasks = subtasksResponse.data.items || [];
      const subtaskList = subtasks.map(subtask => `<li>${subtask.title}</li>`).join('');

      return {
        title: task.title,
        subtasks: subtaskList,
      };
    }));

    // Renderizando a página HTML com as tarefas
    res.render("index", { tasks: taskList });
  } catch (error) {
    console.error("Erro ao obter tarefas ou subtarefas:", error);
    res.send("Erro ao acessar as tarefas.");
  }
});

app.get("/logout", (req, res) => req.logout(() => res.redirect("/")));

app.listen(3000, () => console.log("Servidor rodando em http://localhost:3000"));
