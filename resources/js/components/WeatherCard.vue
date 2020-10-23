<template>
    <div class="card" id="weather">
        <div class="card-header">
            <k-progress
                    status="success"
                    type="line"
                    :percent="modify_interval_percent"
                    :line-height="3"
                    active
                    :show-text="false">
            </k-progress>
            <div class="row">
                <div class="col-6">Weather</div>
                <div class="col-6 text-right">
                    Last modified : {{ last_modified }}
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="h-100 overflow-hidden">
                <div class="hourly">
                    <div v-for="hourly in hourly_list" class="hourly-item">
                        <div class="row">
                            <div class="col-6">
                                <p class="hour">{{ new Date(hourly.dt * 1000).getHours() }}時</p>
                                <p class="temp">{{ hourly.temp }}℃</p>
                            </div>
                            <div class="col-6">
                                <div class="icon" v-for="item in hourly.weather">
                                    <font-awesome-icon icon="sun" v-if="item.icon === '01d'" />
                                    <font-awesome-icon icon="cloud-sun" v-if="item.icon === '02d'" />
                                    <font-awesome-icon icon="cloud" v-if="item.icon === '03d'" />
                                    <font-awesome-icon icon="cloud" v-if="item.icon === '04d'" />
                                    <font-awesome-icon icon="cloud-showers-heavy" v-if="item.icon === '9d'" />
                                    <font-awesome-icon icon="cloud-sun-rain" v-if="item.icon === '10d'" />
                                    <font-awesome-icon icon="bolt" v-if="item.icon === '11d'" />
                                    <font-awesome-icon icon="snowflake" v-if="item.icon === '13d'" />
                                    <font-awesome-icon icon="smog" v-if="item.icon === '50d'" />
                                    <font-awesome-icon icon="moon" v-if="item.icon === '01n'" />
                                    <font-awesome-icon icon="cloud-moon" v-if="item.icon === '02n'" />
                                    <font-awesome-icon icon="cloud" v-if="item.icon === '03n'" />
                                    <font-awesome-icon icon="cloud" v-if="item.icon === '04n'" />
                                    <font-awesome-icon icon="cloud-showers-heavy" v-if="item.icon === '9n'" />
                                    <font-awesome-icon icon="cloud-sun-rain" v-if="item.icon === '10n'" />
                                    <font-awesome-icon icon="bolt" v-if="item.icon === '11n'" />
                                    <font-awesome-icon icon="snowflake" v-if="item.icon === '13n'" />
                                    <font-awesome-icon icon="smog" v-if="item.icon === '50n'" />
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6 text-center precipitation" >
                                    <font-awesome-icon icon="tint" />
                                    <span v-if="hourly.rain">{{ hourly.rain }}</span>
                                    <span v-else>0</span>
                                    mm
                                </div>
                                <div class="col-6 text-center snowfall" >
                                    <font-awesome-icon icon="snowflake" />
                                    <span v-if="hourly.snow"> {{ hourly.snow }}</span>
                                    <span v-else>0</span>
                                    mm
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="daily">
                    <div v-for="daily in daily_list" class="daily-item">
                        <div class="row">
                            <div class="col-6">
                                <p class="date">{{ new Date(daily.dt * 1000).toLocaleDateString() }}</p>
                                <p class="temp-max">{{ daily.temp.max }}℃</p>
                                <p class="temp-min">{{ daily.temp.min }}℃</p>
                            </div>
                            <div class="col-6">
                                <div class="icon" v-for="item in daily.weather">
                                    <font-awesome-icon icon="sun" v-if="item.icon === '01d'" />
                                    <font-awesome-icon icon="cloud-sun" v-if="item.icon === '02d'" />
                                    <font-awesome-icon icon="cloud" v-if="item.icon === '03d'" />
                                    <font-awesome-icon icon="cloud" v-if="item.icon === '04d'" />
                                    <font-awesome-icon icon="cloud-showers-heavy" v-if="item.icon === '9d'" />
                                    <font-awesome-icon icon="cloud-sun-rain" v-if="item.icon === '10d'" />
                                    <font-awesome-icon icon="bolt" v-if="item.icon === '11d'" />
                                    <font-awesome-icon icon="snowflake" v-if="item.icon === '13d'" />
                                    <font-awesome-icon icon="smog" v-if="item.icon === '50d'" />
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6 text-center precipitation" >
                                    <font-awesome-icon icon="tint" />
                                    <span v-if="daily.rain">{{ daily.rain }}</span>
                                    <span v-else>0</span>
                                    mm
                                </div>
                                <div class="col-6 text-center snowfall" >
                                    <font-awesome-icon icon="snowflake" />
                                    <span v-if="daily.snow"> {{ daily.snow }}</span>
                                    <span v-else>0</span>
                                    mm
                                </div>
                            </div>
                        </div>
                   </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                hourly_list: null,
                daily_list: null,
                last_modified: null,
                modify_interval_max: 600 * 20,
                modify_interval_value: 0,
                modify_interval_percent: 0,
                interval_id: null,
            }
        },
        mounted () {
            const self = this;
            // ポーリングでaxios定期実行
            this.interval_id = setInterval((function getWeather() {
                // // 更新インターバルが上限まで達したら更新インターバル初期化
                if (self.modify_interval_value >= self.modify_interval_max) {
                    self.modify_interval_value = 0;
                }

                // 更新インターバルが0ならAPI実行
                if (self.modify_interval_value === 0) {
                    // 気象情報取得
                    axios.get('/api/news').then(response => {
                        axios.get('/api/weather').then(response => {
                            self.hourly_list = response.data.hourly;
                            self.daily_list = response.data.daily;
                        }).catch(err => {
                        }).finally({
                        });
                    }).catch(err => {
                    }).finally({
                    });
                    // 最終更新日時
                    let date = new Date();
                    let year = date.getFullYear();
                    let month = date.getMonth() + 1;
                    let day = date.getDate();
                    self.last_modified = year + '/' + month + '/' + day + ' ' + date.toLocaleTimeString();
                }

                // 更新インターバルを進める
                self.modify_interval_value += 1;
                // 更新インターバルのパーセント計算
                self.modify_interval_percent = (self.modify_interval_value / self.modify_interval_max) * 100;

                // 初回時の実行
                return getWeather;
            }()), 100);
        },
        beforeDestroy () {
            // ページ遷移時にポーリング解除
            clearInterval(this.interval_id);
        },
    }
</script>

<style lang="scss" scoped>
    $temp-color: #c4ffb8;
    $min-color: #b8fff5;
    $max-color: #ffb8b8;

    .hourly, .daily {
        display: flex;
        height: 50%;
        font-size: 1vw;
    }
    .hourly {
        .hourly-item {
            width: 16%;
            margin: 0 2% 10px;
            padding: 5px;
            background: rgba(50, 50, 50, 0.3);
            .hour {
                margin-bottom: 5px;
            }
            .icon {
                font-size: 2.5vw;
            }
            .temp {

                text-align: right;
                color: $temp-color;
                margin: 0;
            }
            .precipitation, .snowfall {
                font-size: 0.8vw;
            }
            .col-6 {
                margin: 0 !important;
            }
        }
    }
    .daily {
        .daily-item {
            width: 16%;
            margin: 0 2%;
            padding: 5px;
            background: rgba(50, 50, 50, 0.3);
            .date {
                margin-bottom: 5px;
            }
            .icon {
                font-size: 2.5vw;
            }
            .temp-min {
                text-align: right;
                color: $min-color;
                margin: 0;
            }
            .temp-max {
                text-align: right;
                color: $max-color;
                margin: 0;
            }
            .precipitation, .snowfall {
                font-size: 0.8vw;
            }
            .col-6 {
                margin: 0 !important;
            }
        }
    }
</style>
