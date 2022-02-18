<template>
  <!-- Question Text and Question Type -->
  <div class="grid grid-cols-12 gap-3">
    <div class="col-span-9">
      <label
        for="question_text"
        class="block text-sm text-gray-600 tracking-wider mb-1 text-left"
        >Question Text</label
      >
      <textarea
        type="text"
        name="question_text"
        v-model="question.question_text"
        class="w-full rounded min-h-[80px]"
      ></textarea>
    </div>
    <div class="col-span-3">
      <label
        for="question_type"
        class="block text-sm text-gray-600 tracking-wider mb-1 text-left"
        >Question Type</label
      >
      <select
        name="question_type"
        v-model="question.question_type"
        class="rounded"
        @change="typeHasChange"
      >
        <option v-for="type in types" :key="type" :value="type">
          {{ type }}
        </option>
      </select>
    </div>
  </div>

  <div class="mt-2">
    <!-- Text -->
    <div v-if="question.question_type == 'text'">
      <label
        for="answer"
        class="block text-sm text-gray-600 tracking-wider mb-1 text-left"
        >Answer</label
      >
      <input
        type="text"
        name="answer"
        class="w-full rounded"
        v-model="question.answer"
      />
    </div>
    <!-- TextArea -->
    <div v-else-if="question.question_type == 'textarea'">
      <label
        for="answer"
        class="block text-sm text-gray-600 tracking-wider mb-1 text-left"
        >Answer</label
      >
      <textarea
        type="text"
        name="answer"
        v-model="question.answer"
        class="w-full rounded min-h-[80px]"
      ></textarea>
    </div>
    <!-- Radio, Select, Checkbox -->
    <div v-else>
      <div class="flex justify-between items-center">
        <label
          for="answer"
          class="block text-sm text-gray-600 tracking-wider mb-1 text-left"
          >Options</label
        >
        <button
          class="p-1 rounded-md bg-blue-500 text-white"
          @click="addOption(this.question.options.length)"
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
            />
          </svg>
        </button>
      </div>
      <div
        v-for="(option, index) in question.options"
        class="grid grid-cols-12 gap-3 mt-2"
      >
        <div class="col-span-9 flex items-center">
          <span class="mr-2 text-xs">{{ index + 1 }}.</span>
          <input
            type="text"
            class="w-full rounded"
            v-model="option.option"
            @input="removeInvalidAnswer"
          />
        </div>

        <div class="col-span-3 flex items-center">
          <!-- Correct Answer -->
          <button
            class="p-2 rounded-md"
            :class="answerColor(option['option'])"
            @click="selectAnswer(option['option'])"
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
                d="M5 13l4 4L19 7"
              />
            </svg>
          </button>
          <!-- Add new Option -->
          <button
            class="p-2 rounded-md text-blue-500"
            @click="addOption(index)"
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
                d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
          </button>
          <!-- Delete Option -->
          <button
            class="p-2 rounded-md text-red-500"
            @click="removeOption(index)"
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
                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
              />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Answer Explanation -->
  <div class="mt-2">
    <label
      for="answer"
      class="block text-sm text-gray-600 tracking-wider mb-1 text-left"
      >Answer Explanation</label
    >
    <textarea
      type="text"
      name="answer"
      v-model="question.answer_explanation"
      class="w-full rounded min-h-[80px]"
      placeholder="optional"
    ></textarea>
  </div>

  <!-- More Info Link -->
  <div class="mt-2">
    <label
      for="more_info_link"
      class="block text-sm text-gray-600 tracking-wider mb-1 text-left"
      >More Info Link</label
    >
    <input
      type="text"
      name="more_info_link"
      class="w-full rounded"
      placeholder="optional"
      v-model="question.more_info_link"
    />
  </div>

  <div v-if="dataError" class="mt-2 bg-red-100 text-left px-4 py-2">
    <span class="text-xs text-red-500 block" v-for="error in dataError">
      {{ error[0] }}
    </span>
  </div>
</template>
<script>
export default {
  props: {
    data: Object,
    dataError: Object,
    mode: String,
  },

  emits: ["updateQuestion", "deleteQuestion"],

  data() {
    return {
      types: ["text", "textarea", "radio", "select", "checkbox"],
      question: JSON.parse(JSON.stringify(this.data)),
    };
  },

  watch: {
    question: {
      handler() {
        this.updateData();
      },
      deep: true,
    },
  },

  methods: {
    getOptions() {
      return this.question.options;
    },

    setOptions(options) {
      this.question.options = options;
    },

    addOption(index) {
      this.question.options.length > index
        ? this.question.options.splice(index + 1, 0, { option: "" })
        : this.question.options.splice(index, 0, { option: "" });
    },

    removeOption(index) {
      this.question.options.splice(index, 1);

      this.removeInvalidAnswer();
    },

    setTextTemplate() {
      this.question.answer = "";
      this.question.options = null;
    },

    setChoiceTemplate() {
      this.setOptions(this.getOptions() || [{ option: "" }]);
      this.question.answer = [];
    },

    typeHasChange() {
      this.isChoiceType() ? this.setChoiceTemplate() : this.setTextTemplate();
    },

    isChoiceType() {
      return ["radio", "select", "checkbox"].includes(
        this.question.question_type
      );
    },

    updateData() {
      if (this.mode === "edit") return;

      let data = JSON.parse(JSON.stringify(this.question));
      this.$emit("updateQuestion", data);
    },

    answerColor(option) {
      return this.question.answer.includes(option) ? "text-green-500" : "";
    },

    selectAnswer(option) {
      if (option.trim() == "") return;

      if (["radio", "select"].includes(this.question.question_type)) {
        this.question.answer = [];
        this.question.answer.push(option);
      } else {
        if (!this.question.answer.includes(option)) {
          this.question.answer.push(option);
        } else {
          this.question.answer = this.question.answer.filter(
            (answer) => answer != option
          );
        }
      }
    },

    removeInvalidAnswer() {
      this.question.answer = this.question.answer.filter((answer) =>
        this.question.options.find((option) => option.option == answer)
      );
    },
  },
};
</script>
<style></style>
