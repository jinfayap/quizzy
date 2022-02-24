<template>
  <div class="flex justify-end mb-2">
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
      <template v-slot:heading> Create new Permission </template>
      <template v-slot:body>
        <div class="mt-2">
          <label
            for="name"
            class="block text-sm text-gray-600 tracking-wider mb-1 text-left"
            >Permission name</label
          >
          <input
            type="text"
            name="name"
            class="w-full rounded"
            v-model="permission.name"
          />

          <div v-if="createError" class="mt-2 bg-red-100 text-left px-4 py-2">
            <span
              class="text-xs text-red-500 block"
              v-for="error in createError"
            >
              {{ error[0] }}
            </span>
          </div>
        </div>
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

  <div class="flex flex-col">
    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
        <div
          class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg"
        >
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  No.
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Permission name
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="(permission, index) in permissions">
                <td class="px-6 py-4 whitespace-nowrap">{{ index + 1 }}.</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  {{ permission.name }}
                </td>

                <td class="px-6 py-4 text-sm font-medium flex justify-end">
                  <modal ref="deletePermission">
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
                      You are about to delete this permission!
                    </template>

                    <template v-slot:body>
                      <p>
                        Your action is irreversible. Ensure you know what you
                        are doing. Click cancel if you do not wish to delete
                        this permission!
                      </p>
                    </template>

                    <template v-slot:button>
                      <button
                        type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-400 text-base font-medium text-white hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                        @click="deletePermission(permission)"
                      >
                        Delete
                      </button>
                    </template>
                  </modal>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      permission: {
        name: "",
      },
      permissions: [],
      createError: null,
    };
  },

  created() {
    axios.get("/api/permission").then((response) => {
      this.permissions = response.data.permissions;
    });
  },

  methods: {
    deletePermission(permission) {
      axios
        .delete(`/permission/${permission.id}`)
        .then((response) => {
          this.$refs.deletePermission.isOpen = false;
          this.permissions = this.permissions.filter(
            (p) => p.id != permission.id
          );
        })
        .catch((error) => {
          console.log(error);
        });
    },

    submit() {
      axios
        .post("/permission", this.permission)
        .then((response) => {
          this.$refs.new.isOpen = false;
          this.permissions.push(response.data.permission);
        })
        .catch((error) => {
          this.createError = error.response.data.errors;
        });
    },
  },
};
</script>
