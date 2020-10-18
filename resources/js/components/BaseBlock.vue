<template>
    <div id="main-container" class="container">
        <div id="row-one" class="row flex-nowrap">
            <div class="col-3">
                <clock-card></clock-card>
            </div>
            <div class="col-9">
                <train-card></train-card>
            </div>
        </div>
        <div id="row-two" class="row flex-nowrap">
            <div class="col-12">
                <weather-card></weather-card>
            </div>
        </div>
        <div id="row-three" class="row flex-nowrap">
            <div class="col-12">
                <news-card></news-card>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
        },
    }
</script>
