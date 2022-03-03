require("./bootstrap");

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

// Emitter for vue flash message
import Emitter from "tiny-emitter";

const emitter = new Emitter();

window.events = emitter;

window.flash = function (message, type) {
  window.events.emit("flash", message, type);
};

import Form from "./core/Form.js";

window.Form = Form;

import { createApp } from "vue";

const app = createApp({});

app.component("quiz-view", require("../components/QuizView.vue").default);
app.component("test-view", require("../components/TestView.vue").default);
app.component(
  "quiz-settings",
  require("../components/QuizSettings.vue").default
);
app.component("modal", require("../components/Modal.vue").default);
app.component("flash", require("../components/Flash.vue").default);
app.component("role", require("../components/admin/Role.vue").default);
app.component(
  "permission",
  require("../components/admin/Permission.vue").default
);
app.component(
  "role-permission",
  require("../components/admin/RolePermission.vue").default
);
app.component(
  "user-role-permission",
  require("../components/admin/UserRolePermission.vue").default
);

app.mount("#app");
