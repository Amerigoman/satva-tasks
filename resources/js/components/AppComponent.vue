<template>
    <div>
        <loader-component :is-visible='isLoading'></loader-component>
        <satva-list-component></satva-list-component>
    </div>
</template>

<script>
    export default {
        name: 'App',
        data: function () {
            return {
                isLoading: this.$root.isLoading,
            }
        }
    }
</script>
