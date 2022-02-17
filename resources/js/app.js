require("./bootstrap");

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

import { createApp } from "vue";

const app = createApp({});

app.component("quiz-view", require("../components/QuizView.vue").default);
app.component("modal", require("../components/Modal.vue").default);

app.mount("#app");
