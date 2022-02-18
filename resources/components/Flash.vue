<template>
  <transition name="slide-fade">
    <div
      class="fixed bottom-5 right-5 px-4 py-2 min-w-[100px] text-center rounded-md text-sm text-white z-10 pointer-events-none"
      :class="color"
      v-show="show"
    >
      {{ body }}
    </div>
  </transition>
</template>
<script>
export default {
  props: ["message"],
  data() {
    return {
      body: this.message,
      show: false,
      color: "bg-blue-400",
    };
  },

  created() {
    if (this.message) {
      this.flash(this.message);
    }

    window.events.on("flash", (message, type = "primary") => {
      this.flash(message, type);
    });
  },

  methods: {
    flash(message, type = "primary") {
      const colors = {
        primary: "bg-blue-400",
        secondary: "bg-gray-400",
        success: "bg-green-400",
        danger: "bg-red-400",
        warning: "bg-yellow-400",
        info: "bg-sky-400",
      };

      this.color = colors[type];
      this.body = message;
      this.show = true;

      this.hide();
    },

    hide() {
      setTimeout(() => {
        this.show = false;
      }, 3000);
    },
  },
};
</script>
<style>
.slide-fade-enter-active {
  transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
  transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateX(20px);
  opacity: 0;
}
</style>
