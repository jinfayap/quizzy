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

import { createApp } from "vue";

const app = createApp({});

app.component("quiz-view", require("../components/QuizView.vue").default);
app.component("modal", require("../components/Modal.vue").default);
app.component("flash", require("../components/Flash.vue").default);

app.mount("#app");
