<template>
    <div id="clock" class="card">
        <h1 class="card-header">{{ app_name }}</h1>
        <div class="card-body">
            <div class="overflow-hidden h-100 row align-items-end">
                <p class="date col-12">{{ date }}</p>
                <p class="time col-12">{{ time }}</p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                now: new Date(),
                app_name: null
            }
        },
        mounted () {
            axios.get('/api/clock').then(response => {
                this.app_name = response.data;
            }).catch(err => {
            }).finally({
            });
        },
        computed: {
            date: function () {
                let year = this.now.getFullYear();
                let month = this.now.getMonth() + 1;
                let day = this.now.getDate();
                let day_of_week = this.now.getDay();
                let day_of_week_str = [ '日', '月', '火', '水', '木', '金', '土' ][day_of_week] ;
                return year + '/' + month + '/' + day + '(' + day_of_week_str + ')';
            },
            time: function () {
                return this.now.toLocaleTimeString();
            }
        },
        methods: {
            refresh: function () {
                this.now = new Date();
                const self = this;
                setTimeout(() => { self.refresh() }, 100);
            }
        },
        created: function () {
            this.refresh();
        }
    }
</script>

<style lang="scss" scoped>
    .card-header {
        font-size: 2vw;
    }
    .date, .time {
        margin-bottom: 0 !important;
        line-height: 1;
    }
    .date {
        font-size: 2vw;
    }
    .time {
        font-size: 4vw;
    }
</style>