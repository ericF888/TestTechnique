<template>
    <section>
        <h2>Personnages</h2>
        <ul>
            <li v-for="character in characters" :key="character.id">
                {{ character.first_name }} {{ character.last_name }}
            </li>
        </ul>
    </section>
</template>

<script>
export default {
    name: 'Characters',
    props: {
        characters: Array
    }
}
</script>

