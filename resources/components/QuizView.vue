<template>
  <!-- Questions -->
  <section class="mt-4">
    <div class="flex justify-between">
      <h2 class="font-bold text-2xl">Questions</h2>

      <modal ref="new">
        <template v-slot:trigger>
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
        </template>

        <template v-slot:icon>
          <div class="bg-blue-500 rounded-full text-white p-2">
            <svg
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
              />
            </svg>
          </div>
        </template>

        <template v-slot:heading> Create new question Form </template>

        <template v-slot:body>
          <question-editor
            :data="question"
            :data-error="errors"
            @updateQuestion="updateFormQuestion"
            mode="create"
          ></question-editor>
        </template>

        <template v-slot:button>
          <button
            type="button"
            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-400 text-base font-medium text-white hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
            @click="submit"
          >
            Create
          </button>
        </template>
      </modal>
    </div>

    <question
      v-for="(question, index) in quiz.questions"
      :data="question"
      :index="index"
      @deleteQuestion="deleteQuestion"
      @updateQuestion="updateQuestion"
    ></question>
  </section>
</template>
<script>
import question from "./Question.vue";
import QuestionEditor from "./editor/QuestionEditor.vue";
export default {
  props: {
    data: Object,
  },

  components: { question, QuestionEditor },

  data() {
    return {
      quiz: "",
      question: {
        question_text: "",
        options: null,
        answer: "",
        question_type: "text",
        answer_explanation: "",
        more_info_link: "",
      },

      errors: null,
    };
  },

  mounted() {
    this.quiz = JSON.parse(JSON.stringify(this.data));
  },

  methods: {
    updateFormQuestion(data) {
      this.question = data;
    },

    deleteQuestion(index) {
      this.quiz.questions.splice(index, 1);
    },

    updateQuestion(index, data) {
      this.quiz.questions[index] = data;
    },

    submit() {
      axios
        .post(`/quiz/${this.quiz.id}/question`, this.question)
        .then(({ data }) => {
          this.$refs.new.isOpen = false;
          this.quiz.questions.push(data.question);
          this.question = {
            question_text: "",
            options: null,
            answer: "",
            question_type: "text",
            answer_explanation: "",
            more_info_link: "",
          };
          flash("Question has been created", "info");
        })
        .catch((error) => {
          this.errors = error.response.data.errors;
          flash("Failed to create the question", "warning");
        });
    },
  },
};
</script>
