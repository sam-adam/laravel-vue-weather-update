<script>
import { fetchWithTimeout } from '../helpers/fetch';
import { MapIcon } from "@heroicons/vue/24/outline"
import { CloudIcon } from "@heroicons/vue/24/outline"
import { ClockIcon } from "@heroicons/vue/24/outline"

export default {
  components: { MapIcon, CloudIcon, ClockIcon },
  data: () => ({
    users: []
  }),
  created() {
    this.fetchUsers()
  },
  methods: {
    async fetchUsers() {
      const url = import.meta.env.VITE_API_URL + '/v1/users'

      this.users = await (await fetchWithTimeout(url, { timeout: 500 })).json()
    }
  }
}
</script>

<template>
  <div class="bg-white">
    <div class="mx-auto max-w-2xl py-16 px-4 sm:py-24 sm:px-6 lg:max-w-7xl lg:px-8">
      <h2 class="text-2xl font-bold tracking-tight text-gray-900">Users</h2>
      <div class="mt-6 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
        <div v-for="user in users" :key="user.id" class="shadow rounded-md p-4 group relative">
          <div class="flex flex-col justify-between space-y-3">
            <h3 class="text-base text-black">
              <a class="font-bold" :href="'/users/' + user.id">
                {{ user.name }}
              </a>
            </h3>
            <div class="space-y-1">
              <div class="flex flex-row space-x-2 text-sm items-center">
                <MapIcon class="h-6 w-6 text-blue-500" />
                <div>{{ user.latitude }}, {{ user.longitude }}</div>
              </div>
              <div class="flex flex-row space-x-2 text-sm items-center">
                <CloudIcon class="h-6 w-6 text-blue-500" />
                <template v-if="user.lastWeatherUpdate">
                  <div>{{ user.lastWeatherUpdate.label }}, {{ user.lastWeatherUpdate.description }}</div>
                </template>
                <template v-else>
                  <div>-</div>
                </template>
              </div>
              <div class="flex flex-row space-x-2 text-sm items-center">
                <ClockIcon class="h-6 w-6 text-blue-500" />
                <template v-if="user.lastWeatherUpdate">
                  <div>{{ user.lastWeatherUpdate.time }}</div>
                </template>
                <template v-else>
                  <div>-</div>
                </template>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
