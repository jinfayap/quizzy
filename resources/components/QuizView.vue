<template>
  <!-- Quiz Information -->
  <section class="bg-sky-200 p-5 rounded-md">
    <h2 class="font-bold text-center text-2xl">Basic Quiz Information</h2>

    <div class="mt-2">
      <label for="title" class="block text-gray-500 text-sm mb-1"
        >Title :</label
      >
      <input
        type="text"
        name="title"
        class="w-full rounded-md"
        required
        v-model="quiz.title"
      />
    </div>
    <div class="mt-2">
      <label
        for="description"
        class="block text-gray-500 text-sm text-uppercase mb-1"
        >Description :</label
      >
      <textarea
        name="description"
        rows="3"
        class="w-full rounded-md placeholder:text-sm min-h-[100px]"
        placeholder="optional"
        v-model="quiz.description"
      ></textarea>
    </div>
    <div class="mt-2">
      <label
        for="description"
        class="block text-gray-500 text-sm text-uppercase mb-1"
        >Duration (Minutes) :
      </label>
      <input
        type="text"
        name="duration"
        class="w-full rounded-md placeholder:text-sm"
        placeholder="optional"
        v-model="quiz.duration"
      />
    </div>

    <div class="mt-4 flex justify-end">
      <button
        class="bg-blue-400 hover:bg-blue-500 hover:shadow-md px-4 py-2 text-white rounded-md"
      >
        Save
      </button>
    </div>
  </section>

  <!-- Questions -->
  <section class="mt-4">
    <div class="flex justify-between">
      <h2 class="font-bold text-2xl">Questions</h2>
      <button
        class="p-2 rounded-md bg-blue-400 hover:bg-blue-500 text-white flex items-center"
      >
        <svg
          class="h-4 w-4"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M12 4v16m8-8H4"
          /></svg
        ><span class="ml-1 hidden lg:block text-xs">New</span>
      </button>
    </div>

    <question
      v-for="(question, index) in quiz.questions"
      :data="question"
      :index="index"
    ></question>
  </section>
</template>
<script>
import question from "./Question.vue";
export default {
  props: {
    data: Object,
  },

  components: { question },

  data() {
    return {
      quiz: "",
    };
  },

  mounted() {
    this.quiz = JSON.parse(JSON.stringify(this.data));
  },
};
</script>
