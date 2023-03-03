<script>
export default {
  data: () => ({
    temperatureUnit: 'CELSIUS',
    windSpeedUnit: 'KMH'
  }),

  methods: {
    updatePreference() {
      this.$emit('update', {
        temperatureUnit: this.temperatureUnit,
        windSpeedUnit: this.windSpeedUnit
      })
    }
  }
}
</script>
<template>
  <div class="flex flex-col shadow p-4 rounded">
    <h4 class="text-lg font-medium mb-3">Unit Preference</h4>
    <div class="space-y-3">
      <div class="flex flex-col">
        <label class="text-base font-medium mb-1">Temperature</label>
        <select v-model="temperatureUnit" class="bg-white rounded-lg px-3 py-2 border" @change="updatePreference">
          <option value="CELSIUS">Celcius</option>
          <option value="FAHRENHEIT">Fahrenheit</option>
          <option value="KELVIN">Kelvin</option>
        </select>
      </div>
      <div class="flex flex-col">
        <label class="text-base font-medium mb-1">Wind Speed</label>
        <select v-model="windSpeedUnit" class="bg-white rounded-lg px-3 py-2 border" @change="updatePreference">
          <option value="KMH">km/h</option>
          <option value="MPH">m/h</option>
          <option value="MS">m/s</option>
        </select>
      </div>
    </div>
  </div>
</template>