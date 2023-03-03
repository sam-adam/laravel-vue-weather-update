<script>
import { fetchWithTimeout, post } from '../helpers/fetch';
import { MapIcon } from "@heroicons/vue/24/outline"
import { CloudIcon } from "@heroicons/vue/24/outline"
import { ClockIcon } from "@heroicons/vue/24/outline"

export default {
  components: { MapIcon, CloudIcon, ClockIcon },
  data: () => ({
    user: null,
    isUpdatingWeather: false
  }),
  created() {
    this.fetchUser()
  },
  methods: {
    async fetchUser() {
      const url = import.meta.env.VITE_API_URL + '/v1/users/' + this.$route.params['id']

      this.user = await (await fetchWithTimeout(url, { timeout: 500 })).json()
    },
    async refreshWeather() {
      this.isUpdatingWeather = true

      const url = import.meta.env.VITE_API_URL + '/v1/users/' + this.$route.params['id'] + '/refresh-weather'

      await (await post(url)).json()
      await this.fetchUser()

      this.isUpdatingWeather = false
    }
  }
}
</script>

<template>
  <div v-if="user" class="bg-white">
    <div class="mx-auto max-w-2xl py-16 px-4 sm:py-24 sm:px-6 lg:max-w-7xl lg:px-8">
      <h2 class="text-2xl font-bold tracking-tight text-gray-900">{{ user.name }}</h2>
      <div class="mt-6">
        <div class="shadow p-4 w-full space-y-3">
          <div>
            <div class="font-bold mb-2">
              Coodinate
            </div>
            <div>
              {{ user.latitude }}, {{ user.longitude }}
            </div>
          </div>
          <div class="flex flex-row items-center space-x-2">
            <div class="flex-1">
              <div class="font-bold mb-2">
                Last update
              </div>
              <div>
                <template v-if="user.lastWeatherUpdate">
                  {{ user.lastWeatherUpdate.time }}
                </template>
                <template v-else>
                  -
                </template>
              </div>
            </div>
            <div>
              <div v-if="isUpdatingWeather">Updating...</div>
              <button v-else @click="refreshWeather" class="inline-flex justify-center rounded-lg text-sm font-semibold py-2 px-3 bg-slate-900 text-white hover:bg-slate-700">Update Now</button>
            </div>
          </div>
          <div>
            <div class="font-bold mb-2">
              Weather
            </div>
            <div>
              <template v-if="user.lastWeatherUpdate">
                {{ user.lastWeatherUpdate.label }}, {{ user.lastWeatherUpdate.description }}
              </template>
              <template v-else>
                -
              </template>
            </div>
          </div>
          <div>
            <div class="font-bold mb-2">
              Temperature
            </div>
            <div>
              <template v-if="user.lastWeatherUpdate">
                {{ user.lastWeatherUpdate.temperature }} {{ user.lastWeatherUpdate.temperature_unit }}
              </template>
              <template v-else>
                -
              </template>
            </div>
          </div>
          <div>
            <div class="font-bold mb-2">
              Wind Speed
            </div>
            <div>
              <template v-if="user.lastWeatherUpdate">
                {{ user.lastWeatherUpdate.wind_speed }} {{ user.lastWeatherUpdate.wind_speed_unit }}
              </template>
              <template v-else>
                -
              </template>
            </div>
          </div>
          <div>
            <div class="font-bold mb-2">
              Wind Direction
            </div>
            <div>
              <template v-if="user.lastWeatherUpdate">
                {{ user.lastWeatherUpdate.wind_direction }}
              </template>
              <template v-else>
                -
              </template>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
