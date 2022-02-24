<template>
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
                  Role name
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Associated Permissions
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
              <tr v-for="(role, index) in roles" :key="role.id">
                <td class="px-6 py-4 whitespace-nowrap">{{ index + 1 }}.</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ role.name }}</td>
                <td>
                  <div
                    v-for="permission in role.permissions"
                    :key="permission.id"
                    class="inline-flex ml-2 mt-2 text-xs px-2 py-1 bg-gray-300 text-white rounded-3xl pointer-events-none"
                  >
                    {{ permission.name }}
                  </div>
                </td>

                <td
                  class="px-6 py-4 text-sm font-medium flex justify-end space-y-2 flex-col"
                >
                  <modal ref="test">
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
                        ><span class="ml-1 hidden lg:block text-xs">Add</span>
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

                    <template v-slot:heading>
                      Add a new permission to the {{ role.name }}!
                    </template>

                    <template v-slot:body>
                      <select
                        name="permission"
                        v-model="selectedPermission"
                        class="rounded"
                      >
                        <option value="" selected disabled>
                          Please choose a permission
                        </option>
                        <option
                          v-for="permission in permissions"
                          :key="permission.id"
                          :value="permission.id"
                        >
                          {{ permission.name }}
                        </option>
                      </select>

                      <div>
                        <h3 class="text-lg font-semibold mt-2">
                          Current Permissions
                        </h3>
                        <div
                          v-for="permission in role.permissions"
                          class="inline-flex ml-2 mt-2 text-xs px-2 py-1 bg-gray-300 text-white rounded-3xl pointer-events-none"
                        >
                          {{ permission.name }}
                        </div>
                      </div>
                    </template>

                    <template v-slot:button>
                      <button
                        type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-400 text-base font-medium text-white hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                        @click="addPermission(role)"
                      >
                        Add
                      </button>
                    </template>
                  </modal>

                  <!-- <modal ref="deletePermission">
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
                        <span class="ml-1 hidden lg:block text-xs">Remove</span>
                      </button>
                    </template>

                    <template v-slot:heading>
                      You are about to remove a permission from this
                      {{ role.name }} role!
                    </template>

                    <template v-slot:body>
                      <p class="mb-2">
                        Your action is irreversible. Ensure you know what you
                        are doing. Click cancel if you do not wish to remove any
                        permission from this {{ role.name }}!
                      </p>

                      <select
                        name="permission"
                        v-model="selectedPermission"
                        class="rounded"
                      >
                        <option value="" selected disabled>
                          Please choose a permission
                        </option>
                        <option
                          v-for="permission in role.permissions"
                          :key="permission.id"
                          :value="permission.id"
                        >
                          {{ permission.name }}
                        </option>
                      </select>

                      <div>
                        <h3 class="text-lg font-semibold mt-2">
                          Current Permissions
                        </h3>
                        <div
                          v-for="permission in role.permissions"
                          class="inline-flex ml-2 mt-2 text-xs px-2 py-1 bg-gray-300 text-white rounded-3xl pointer-events-none"
                        >
                          {{ permission.name }}
                        </div>
                      </div>
                    </template>

                    <template v-slot:button>
                      <button
                        type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-400 text-base font-medium text-white hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                        @click="removePermission(role)"
                      >
                        Remove
                      </button>
                    </template>
                  </modal> -->
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
      role: {
        name: "",
      },
      permissions: [],
      roles: [],
      createError: null,
      selectedPermission: "",
    };
  },

  created() {
    axios.get("/api/role-permission").then((response) => {
      this.roles = response.data.roles;
    });

    axios.get("/api/permission").then((response) => {
      this.permissions = response.data.permissions;
    });
  },

  methods: {
    addPermission(role) {
      axios
        .post(`/role/${role.id}/permission/${this.selectedPermission}`)
        .then((response) => {
          this.roles = this.roles.filter((r) => {
            if ((r.id = role.id)) {
              r.permissions.push(
                this.permissions.find((p) => p.id == this.selectedPermission)
              );
            }
            return r;
          });

          this.$refs.test.isOpen = false;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    removePermission(role) {
      axios
        .delete(`/role/${role.id}/permission/${this.selectedPermission}`)
        .then((response) => {
          this.$refs.deletePermission.isOpen = false;

          this.roles = this.roles.filter((r) => {
            if ((r.id = role.id)) {
              r.permissions.push(
                this.permissions.find((p) => p.id == this.selectedPermission)
              );
            }
            return r;
          });
        });
    },
  },
};
</script>
