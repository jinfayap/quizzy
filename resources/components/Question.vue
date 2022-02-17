<template>
  <section>
    <!-- Question Data -->
    <article class="space-y-2">
      <!-- Question Text -->
      <div class="mt-5">
        <div class="flex justify-between items-center">
          <span class="block text-lg text-gray-500 tracking-wide">
            Question {{ index + 1 }}</span
          >
          <!-- FAB Buttons -->
          <div class="flex justify-end space-x-2">
            <modal>
              <template v-slot:trigger>
                <button
                  class="p-2 rounded-md bg-green-400 hover:bg-green-500 text-white flex items-center"
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
                      d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                    /></svg
                  ><span class="ml-1 hidden lg:block text-xs">Edit</span>
                </button>
              </template>
            </modal>

            <modal>
              <template v-slot:trigger>
                <button
                  class="p-2 rounded-md bg-red-400 hover:bg-red-500 text-white flex items-center"
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
                  <span class="ml-1 hidden lg:block text-xs">Delete</span>
                </button>
              </template>
            </modal>
          </div>
        </div>
        <p>{{ question.question_text }}</p>
      </div>

      <div>
        <div v-if="isTextQuestion">
          <span class="block text-sm text-gray-500 tracking-wide">Answer:</span>
          <p>{{ question.answer }}</p>
        </div>
        <div v-else-if="isChoiceQuestion">
          <span class="block text-sm text-gray-500 tracking-wide mb-1"
            >Options</span
          >
          <div class="grid grid-cols-2 gap-2">
            <div
              v-for="(option, index) in question.options"
              class="border p-2"
              :class="[
                isCorrectAnswer(option['option'])
                  ? 'border-green-500'
                  : 'border-red-500',
              ]"
            >
              <div class="flex items-center">
                <span class="flex-1"
                  >{{ index + 1 }}. {{ option["option"] }}</span
                >
                <div>
                  <span
                    v-if="isCorrectAnswer(option['option'])"
                    class="text-green-400"
                    ><svg
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
                      /></svg
                  ></span>
                  <span
                    v-if="!isCorrectAnswer(option['option'])"
                    class="text-red-400"
                    ><svg
                      class="h-4 w-4"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                      />
                    </svg>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div v-if="question.answer_explanation">
        <span class="block text-sm text-gray-500 tracking-wide"
          >Answer Explanation:</span
        >
        <p>{{ question.answer_explanation }}</p>
      </div>

      <span
        class="flex items-center text-sm text-gray-500 tracking-wide"
        v-if="question.more_info_link"
        >More Info Link:
        <button class="p-2 rounded-md">
          <a
            :href="question.more_info_link"
            class="flex items-end text-purple-400 hover:text-purple-500"
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
                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
              /></svg
            ><span class="ml-1 hidden lg:block text-xs">Link</span>
          </a>
        </button></span
      >
    </article>
  </section>

  <hr />
</template>
<script>
export default {
  props: {
    data: Object,
    index: Number,
  },

  data() {
    return {
      question: this.data,
    };
  },

  methods: {
    isCorrectAnswer(option) {
      return this.question.answer.includes(option);
    },
  },

  computed: {
    isTextQuestion() {
      return ["text", "textarea"].includes(this.question.question_type);
    },

    isChoiceQuestion() {
      return ["radio", "select", "checkbox"].includes(
        this.question.question_type
      );
    },
  },
};
</script>
