require("./bootstrap");

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

import { createApp } from "vue";

const app = createApp({});

app.component(
  "example-component",
  require("../components/ExampleComponent.vue").default
);

app.mount("#app");
