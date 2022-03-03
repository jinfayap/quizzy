<template>
  <div class="grid grid-cols-12">
    <div class="col-span-8">
      <!-- Quiz Information -->
      <section class="p-5 rounded-md">
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
            @keydown="quiz.errors.clear('title')"
          />
          <span
            class="text-xs text-red-500 block"
            v-if="quiz.errors.has('title')"
            v-text="quiz.errors.get('title')"
          >
          </span>
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
            @keydown="quiz.errors.clear('duration')"
          />
          <span
            class="text-xs text-red-500 block"
            v-if="quiz.errors.has('duration')"
            v-text="quiz.errors.get('duration')"
          >
          </span>
        </div>

        <div class="mt-2">
          <label
            for="public"
            class="block text-gray-500 text-sm text-uppercase mb-1"
            >Public :
          </label>

          <div>
            <input type="radio" :value="0" v-model="quiz.public" />
            <label for="two" class="ml-2">No</label>
          </div>

          <div>
            <input type="radio" :value="1" v-model="quiz.public" />
            <label for="one" class="ml-2">Yes</label>
          </div>
        </div>

        <div class="mt-4 flex justify-end">
          <button
            class="bg-blue-400 hover:bg-blue-500 hover:shadow-md px-4 py-2 text-white rounded-md"
            @click="save"
          >
            Save
          </button>
        </div>
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

      <section class="mt-3" v-if="!isPublic">
        <h2 class="font-semibold text-lg mb-3">Invite User to take quiz</h2>

        <form @submit.prevent="invite" class="space-y-2">
          <div>
            <label class="text-sm">User email</label>
            <input
              type="text"
              class="rounded w-full"
              v-model="inviteForm.email"
              @keydown="inviteForm.errors.clear('email')"
            />
            <span
              class="text-xs text-red-500 block"
              v-if="inviteForm.errors.has('email')"
              v-text="inviteForm.errors.get('email')"
            >
            </span>
          </div>
          <div>
            <label class="text-sm">Start date</label>
            <input
              type="date"
              class="rounded w-full"
              v-model="inviteForm.start_date"
            />
          </div>
          <div>
            <label class="text-sm">End date</label>
            <input
              type="date"
              class="rounded w-full"
              v-model="inviteForm.end_date"
            />
          </div>

          <button
            class="w-full mt-3 lg:ml-1 lg:mt-0 bg-blue-400 hover:bg-blue-500 hover:shadow-md px-4 py-2 text-white rounded-md"
            type="submit"
          >
            Give access
          </button>
        </form>
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
      quiz: new Form(JSON.parse(JSON.stringify(this.data))),
      inviteForm: new Form({
        email: "",
        start_date: "",
        end_date: "",
      }),
      isPublic: this.data.public,
    };
  },

  methods: {
    deleteQuiz() {
      this.quiz
        .delete(`/quiz/${this.quiz.id}`)
        .then((response) => {
          location.href = "/quiz";
          flash("Quiz deleted", "success");
        })
        .catch((error) => {
          flash("Error in deleting the quiz", "danger");
        });
    },

    invite() {
      this.inviteForm
        .post(`/invite/quiz/${this.quiz.id}`)
        .then((response) => {
          flash("Success in sending test invite", "success");
        })
        .catch((error) => {
          flash(
            "Failed to send test invite to the user with this email",
            "danger"
          );
        });
    },

    save() {
      this.quiz
        .patch(`/quiz/${this.quiz.id}`)
        .then((response) => {
          flash("Quiz information updated");
          this.isPublic = this.quiz.public;
        })
        .catch((error) => {
          flash("Error in updating the quiz information", "danger");
        });
    },
  },
};
</script>
<style></style>
