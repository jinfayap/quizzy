<template>
  <div class="grid grid-cols-12">
    <div class="col-span-8">
      <section class="mr-2">
        <h2 class="font-bold text-center text-2xl">Quiz Information</h2>

        <div class="mt-2">
          <span class="text-gray-500 text-sm mr-2">Title :</span>
          <span>{{ quiz.title }}</span>
        </div>

        <div class="mt-2">
          <span class="text-gray-500 text-sm mr-2">Description :</span>
          <span>{{ quiz.description }}</span>
        </div>

        <div class="mt-2">
          <span class="text-gray-500 text-sm mr-2">Duration :</span>
          <span>{{ quiz.duration }}</span>
        </div>

        <hr class="mt-2" />
      </section>

      <section class="mt-2">
        <h2 class="font-bold text-2xl text-center">Quick Comments</h2>
        <h2 class="font-semibold text-lg">WIP</h2>
      </section>
    </div>

    <div class="col-span-4 border-l border-gray-400 pl-5">
      <section>
        <div>
          <span class="mr-2 font-semibold">Creator:</span>
          <span class="text-sm">{{ quiz.creator.name }}</span>
        </div>
        <div>
          <span class="mr-2 font-semibold">Created:</span>
          <span class="text-sm">{{ quiz.created_at }}</span>
        </div>
        <div>
          <span class="mr-2 font-semibold">Last updated:</span>
          <span class="text-sm">{{ quiz.updated_at }}</span>
        </div>
      </section>

      <section class="mt-3 flex">
        <modal ref="deleteQuiz">
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

          <template v-slot:heading>
            You are about to delete this quiz!
          </template>

          <template v-slot:body>
            <p>
              Your action is irreversible. Ensure you know what you are doing.
              Click cancel if you do not wish to delete this quiz!
            </p>
          </template>

          <template v-slot:button>
            <button
              type="button"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-400 text-base font-medium text-white hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
              @click="deleteQuiz"
            >
              Delete
            </button>
          </template>
        </modal>

        <button
          class="ml-3 p-2 rounded-md bg-green-400 hover:bg-green-500 text-white"
        >
          <a :href="'/quiz/' + quiz.id + '/edit'" class="flex items-center">
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
          </a>
        </button>
      </section>

      <section class="mt-3">
        <h2 class="font-semibold text-lg mb-3">Add a collaborator!</h2>
        <div class="lg:flex">
          <input type="text" class="rounded w-full" placeholder="Email..." />
          <button
            class="w-full mt-3 lg:ml-1 lg:mt-0 lg:w-min bg-blue-400 hover:bg-blue-500 hover:shadow-md px-4 py-2 text-white rounded-md"
          >
            Add
          </button>
        </div>
      </section>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    data: Object,
  },

  data() {
    return {
      quiz: JSON.parse(JSON.stringify(this.data)),
    };
  },

  methods: {
    deleteQuiz() {
      axios
        .delete(`/quiz/${this.quiz.id}`)
        .then((response) => {
          location.href = "/quiz";
        })
        .catch((error) => {
          console.log("Error in deleting the quiz");
        });
    },
  },
};
</script>
<style></style>
