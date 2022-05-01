<template>
  <div>
    <div class="p-10">
      <h1 class="text-4xl font-bold">Candidates</h1>
    </div>
    <div
      class="p-10 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-5"
    >
      <div
        v-for="candidate in candidates"
        :key="candidate.id"
        class="rounded overflow-hidden shadow-lg"
      >
        <img class="w-full" src="/avatar.png" alt="" />
        <div class="px-6 py-4">
          <div class="font-bold text-xl mb-2">{{ candidate.name }}</div>
          <p class="text-gray-700 text-base">{{ candidate.description }}</p>
        </div>
        <div class="px-6 pt-4 pb-2">
          <span
            v-for="strength in JSON.parse(candidate.strengths)"
            :key="strength"
            class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2"
          >
            {{ strength }}
          </span>
        </div>
        <div class="p-6 float-right">
          <button
            class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow"
            @click="contact(candidate.id)"
            v-if="! contactedCandidatesIds.includes(candidate.id)"
          >
            Contact
          </button>

          <button
            class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 hover:bg-teal-100 rounded shadow"
            v-if="! contactedCandidatesIds.includes(candidate.id)"
          >
            Hire
          </button>

          <button
            class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 hover:bg-teal-100 rounded shadow"
            @click="hire(candidate.id)"
            v-else-if="! hiredCandidatesIds.includes(candidate.id)"
          >
            Hire
          </button>

          <button
            class="bg-green-500 hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 hover:bg-teal-100 rounded shadow"
            v-else
          >
            Hired
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['candidates', 'company_contacted_candidates_ids', 'company_hired_candidates_ids'],
  data() {
      return {
          contactedCandidatesIds: [],
          hiredCandidatesIds: []
      }
  },
  methods: {
      contact(candidateId) {
          axios.post("/candidates-contact", {'candidate_id': candidateId})
            .then(() => this.contactedCandidatesIds.push(candidateId))
            .catch(console.error);
      },
      hire(candidateId) {
            axios.post("/candidates-hire", {'candidate_id': candidateId})
            .then(() => this.hiredCandidatesIds.push(candidateId))
            .catch(console.error);
      }
  },
  mounted() {
      this.contactedCandidatesIds = JSON.parse(this.company_contacted_candidates_ids);
      this.hiredCandidatesIds = JSON.parse(this.company_hired_candidates_ids);
  }
}
</script>
