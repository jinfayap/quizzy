<template>
  <section>
    <h1 class="text-center font-bold text-2xl">{{ quiz.title }}</h1>
  </section>

  <section class="flex justify-end" v-if="quiz.duration">
    <div>
      <span class="font-bold text-2xl" v-text="time.minute"></span>
      <span class="ml-2 font-semibold">Min</span>
      <span class="ml-2 font-bold text-2xl" v-text="time.second"></span>
      <span class="ml-2 font-semibold">Sec</span>
    </div>
  </section>

  <section class="flex justify-end" v-else>
    <div>
      <span class="font-bold">No Time Limit</span>
    </div>
  </section>
  <!-- Questions -->
  <section>
    <h2 class="font-bold text-2xl">Questions</h2>

    <question-viewer
      v-for="(question, index) in quiz.questions"
      :key="question.id"
      :data="question"
      :index="index"
      @updateAnswer="storeAnswer"
    ></question-viewer>
  </section>

  <div class="mt-4 flex justify-end">
    <modal ref="submitTest">
      <template v-slot:trigger>
        <button
          class="p-2 rounded-md bg-blue-400 hover:bg-blue-500 text-white flex items-center"
        >
          <span class="block text-xs">Submit</span>
        </button>
      </template>

      <template v-slot:heading> Confirm your submission </template>

      <template v-slot:body>
        <p>
          You are about to submit your answer. Please ensure that you answered
          all the questions.
        </p>
      </template>

      <template v-slot:button>
        <button
          type="button"
          class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-400 text-base font-medium text-white hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
          @click="submit"
        >
          Confirm
        </button>
      </template>
    </modal>
  </div>
</template>
<script>
import QuestionViewer from "./viewer/QuestionViewer.vue";
export default {
  props: {
    data: Object,
  },

  components: { QuestionViewer },

  data() {
    return {
      quiz: "",
      answers: {},
      time: {
        minute: 0,
        second: 0,
      },
    };
  },

  mounted() {
    this.quiz = JSON.parse(JSON.stringify(this.data));

    this.quiz.questions.map((question) => {
      this.answers[question.id] = "";
      return;
    });

    if (this.quiz.duration != null) {
      this.time.minute = this.quiz.duration;

      let timer = setInterval(() => {
        if (this.time.second == 0 && this.time.minute > 0) {
          this.time.minute -= 1;
          this.time.second = 59;
        } else {
          this.time.second -= 1;
        }

        if (this.time.minute == 0 && this.time.second == 0) {
          clearInterval(timer);

          this.submit();
        }
      }, 1000);
    }
  },

  methods: {
    storeAnswer(id, answer) {
      this.answers[id] = answer;
    },

    submit() {
      axios
        .post(`/test/quiz/${this.quiz.id}`, { answers: this.answers })
        .then((response) => {
          this.$refs.submitTest.isOpen = false;

          const testId = response.data.test.id;

          if (this.quiz.public) {
            location.href = "/public/result/test/" + testId;
            return;
          }
          location.href = "/result/test/" + testId;
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>
