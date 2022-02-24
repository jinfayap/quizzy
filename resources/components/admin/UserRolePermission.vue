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
                  Name
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Roles
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Permissions
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
              <tr v-for="(user, index) in users" :key="user.id">
                <td class="px-6 py-4 whitespace-nowrap">{{ index + 1 }}.</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      <img
                        class="h-10 w-10 rounded-full"
                        src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=4&w=256&h=256&q=60"
                        alt=""
                      />
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">
                        {{ user.name }}
                      </div>
                      <div class="text-sm text-gray-500">
                        {{ user.email }}
                      </div>
                    </div>
                  </div>
                </td>
                <td>
                  <div
                    v-for="role in user.roles"
                    :key="role.id"
                    class="inline-flex ml-2 mt-2 text-xs px-2 py-1 bg-gray-300 text-white rounded-3xl pointer-events-none"
                  >
                    {{ role.name }}
                  </div>
                </td>
                <td>
                  <div
                    v-for="permission in user.permissions"
                    :key="permission.id"
                    class="inline-flex ml-2 mt-2 text-xs px-2 py-1 bg-gray-300 text-white rounded-3xl pointer-events-none"
                  >
                    {{ permission.name }}
                  </div>
                </td>

                <td
                  class="px-6 py-4 text-sm font-medium flex justify-end space-y-2 flex-col"
                >
                  <modal ref="addRole">
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
                      Add a new role to {{ user.name }}!
                    </template>

                    <template v-slot:body>
                      <select
                        name="add_role"
                        v-model="selectedRole"
                        class="rounded"
                      >
                        <option value="" selected disabled>
                          Please choose a role
                        </option>
                        <option
                          v-for="role in roles"
                          :key="role.id"
                          :value="role.id"
                        >
                          {{ role.name }}
                        </option>
                      </select>

                      <div>
                        <h3 class="text-lg font-semibold mt-2">Current Role</h3>
                        <div
                          v-for="role in user.roles"
                          :key="role.id"
                          class="inline-flex ml-2 mt-2 text-xs px-2 py-1 bg-gray-300 text-white rounded-3xl pointer-events-none"
                        >
                          {{ role.name }}
                        </div>
                      </div>
                    </template>

                    <template v-slot:button>
                      <button
                        type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-400 text-base font-medium text-white hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                        @click="addRole(user, index)"
                      >
                        Add
                      </button>
                    </template>
                  </modal>

                  <modal ref="removeRole">
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
                      You are about to remove a role from
                      {{ user.name }}!
                    </template>

                    <template v-slot:body>
                      <p class="mb-2">
                        Your action is irreversible. Ensure you know what you
                        are doing. Click cancel if you do not wish to remove any
                        role from {{ user.name }}!
                      </p>

                      <select
                        name="delete_role"
                        v-model="selectedRole"
                        class="rounded"
                      >
                        <option value="" selected disabled>
                          Please choose a role
                        </option>
                        <option
                          v-for="role in user.roles"
                          :key="role.id"
                          :value="role.id"
                        >
                          {{ role.name }}
                        </option>
                      </select>

                      <div>
                        <h3 class="text-lg font-semibold mt-2">Current Role</h3>
                        <div
                          v-for="role in user.roles"
                          :key="role.id"
                          class="inline-flex ml-2 mt-2 text-xs px-2 py-1 bg-gray-300 text-white rounded-3xl pointer-events-none"
                        >
                          {{ role.name }}
                        </div>
                      </div>
                    </template>

                    <template v-slot:button>
                      <button
                        type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-400 text-base font-medium text-white hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                        @click="removeRole(user, index)"
                      >
                        Remove
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
      users: [],
      roles: [],
      selectedRole: "",
    };
  },

  created() {
    axios.get("/api/user-role-permission").then((response) => {
      this.users = response.data.users;
    });

    axios.get("/api/role").then((response) => {
      this.roles = response.data.roles;
    });
  },

  methods: {
    addRole(user, index) {
      axios
        .post(`/user/${user.id}/role/${this.selectedRole}`)
        .then((response) => {
          this.$refs.addRole[index].isOpen = false;
          this.users = this.users.filter((u) => {
            if (u.id == user.id) {
              u.roles.push(this.roles.find((r) => r.id == this.selectedRole));
            }
            return u;
          });

          this.selectedRole = "";
        })
        .catch((error) => {});
    },

    removeRole(user, index) {
      axios
        .delete(`/user/${user.id}/role/${this.selectedRole}`)
        .then((response) => {
          this.$refs.removeRole[index].isOpen = false;

          this.users = this.users.filter((u) => {
            if (u.id == user.id) {
              u.roles = u.roles.filter((r) => r.id != this.selectedRole);
            }
            return u;
          });

          this.selectedRole = "";
        });
    },
  },
};
</script>
