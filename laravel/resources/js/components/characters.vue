<template>
    <section class="bg-gray-900">
        <div v-for="character in characters" :key="character.id" class="bg-gray-700 p-4 m-3 rounded-lg shadow-lg flex cursor-pointer"
        @click="showEpisodes(character.id, character.last_name + ' ' + character.first_name)">
            <img :src="character.picture_url" class="w-32 h-32 rounded-lg mr-4">
            <div class="flex flex-col">
            <p class="text-white text-xl font-bold">{{ character.last_name }} {{ character.first_name }}</p>
            <p class="text-green-500 mt-2"> Statut: {{ character.status }}</p>
            <p class="text-gray-400 mt-2"> Lieu de vie: {{ character.origin }}</p>
        </div>
        <div 
        v-if="showModal" 
        class="fixed z-50 inset-0 overflow-y-auto bg-black bg-opacity-50 transition-opacity"
        @click.stop="closeModal">
            <div 
            class="flex items-center justify-center min-h-screen"
            @click.stop >
                <div class="bg-white rounded-lg shadow-xl overflow-hidden max-w-xl w-full">
                    <div class="p-4">
                        <h2 class="text-xl font-semibold">{{ currentCharacterName }}</h2>
                        <button @click="closeModal" class="float-right bg-red-500 text-white rounded-full p-1">X</button>
                        <ul>
                            <li v-for="episode in episodes" :key="episode.id">
                                <h3>{{ episode.name }} - {{ episode.episode }}</h3>
                                <p>Date de diffusion: {{ episode.air_date }}</p>
                                <div class="flex">
                                <img v-for="char in episode.characters" :key="char.id" :src="char.image" alt="character image" class="w-16 h-16 rounded-full m-2">
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
</template>


<script>
export default {
    name: 'Characters',
    props: {
        characters: Array
    },  data() {
    return {
      showModal: false,
      episodes: [],
      currentCharacterName: ''
    };
  },
  methods: {
    async showEpisodes(characterId, characterName) {
      this.currentCharacterName = characterName;
      try {
        let response = await axios.get(`https://rickandmortyapi.com/api/character/${characterId}`);
        const episodeLinks = response.data.episode;
        this.episodes = await Promise.all(episodeLinks.map(link => axios.get(link).then(res => res.data)));
        this.showModal = true;
      } catch (error) {
        console.error("Erreur lors de la récupération des épisodes:", error);
      }
    }
  }
};
</script>


