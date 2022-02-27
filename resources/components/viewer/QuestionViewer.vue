<template>
  <div class="mt-4">
    <span class="block text-sm text-gray-600 tracking-wider mb-1 text-left"
      >Question Text</span
    >
    <p class="inline-block">{{ index + 1 }}. {{ question.question_text }}</p>

    <div class="mt-2">
      <!-- Text Type -->
      <div v-if="question.question_type == 'text'">
        <label
          for="answer"
          class="block text-sm text-gray-600 tracking-wider mb-1 text-left"
          >Your Answer</label
        >
        <input
          type="text"
          name="answer"
          class="w-full rounded"
          v-model="userAnswer"
          @input="updateAnswer"
        />
      </div>

      <div v-else-if="question.question_type == 'textarea'">
        <label
          for="answer"
          class="block text-sm text-gray-600 tracking-wider mb-1 text-left"
          >Your Answer</label
        >
        <textarea
          type="text"
          name="answer"
          class="w-full rounded min-h-[80px]"
          v-model="userAnswer"
          @input="updateAnswer"
        ></textarea>
      </div>
      <!-- Radio -->
      <div v-else-if="question.question_type == 'radio'" class="mt-2">
        <div class="grid grid-cols-2 gap-4">
          <div
            v-for="(option, index) in question.options"
            class="border border-black px-4 py-2 rounded"
          >
            <div class="flex items-center">
              <input
                type="radio"
                :name="'option_' + question.id + '_' + index"
                :value="option['option']"
                v-model="userAnswer"
                @change="updateAnswer"
              />
              <label
                :for="'option_' + question.id + '_' + index"
                class="ml-2 text-sm"
                >{{ option["option"] }}</label
              >
            </div>
          </div>
        </div>
      </div>

      <!-- Select -->
      <div v-else-if="question.question_type == 'select'" class="mt-2">
        <div class="grid grid-cols-2 gap-4">
          <select v-model="userAnswer" @change="updateAnswer" class="rounded">
            <option value="" selected disabled>Pleaase choose an option</option>
            <option
              v-for="option in question.options"
              class="border border-black px-4 py-2 rounded"
              :value="option['option']"
            >
              {{ option["option"] }}
            </option>
          </select>
        </div>
      </div>

      <div v-else-if="question.question_type == 'checkbox'" class="mt-2">
        <div class="grid grid-cols-2 gap-4">
          <div
            v-for="(option, index) in question.options"
            class="border border-black px-4 py-2 rounded"
          >
            <div class="flex items-center">
              <input
                type="checkbox"
                :name="'option_' + question.id + '_' + index"
                :value="option['option']"
                v-model="userAnswer"
                @change="updateAnswer"
                class="rounded"
              />
              <label
                :for="'option_' + question.id + '_' + index"
                class="ml-2 text-sm"
                >{{ option["option"] }}</label
              >
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    data: Object,
    index: Number,
  },

  emits: ["updateAnswer"],

  data() {
    return {
      question: JSON.parse(JSON.stringify(this.data)),
      userAnswer: "",
    };
  },

  mounted() {
    if (["checkbox"].includes(this.question.question_type)) {
      this.userAnswer = [];
      return;
    }
  },

  methods: {
    updateAnswer() {
      this.$emit("updateAnswer", this.question.id, this.userAnswer);
    },
  },
};
</script>
<style></style>
