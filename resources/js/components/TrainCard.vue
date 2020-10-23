<template>
    <div class="card" id="train">
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
                <div class="col-6">Train</div>
                <div class="col-6 text-right">
                    Last modified : {{ last_modified }}
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="h-100 overflow-hidden">
                <div class="row railway-lines">
                    <div class="col-3 railway-line" v-for="railway_line in railway_lines">
                        <span class="company">{{ railway_line.company }}</span>
                        <span class="line">{{ railway_line.line }}</span>
                        <p class="is-delay">
                            <span class="true" v-if="railway_line.is_delay === true ">
                                運行情報あり
                            </span>
                            <span class="false" v-else>
                                運行情報なし
                            </span>
                        </p>
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
                railway_lines: null,
                last_modified: null,
                modify_interval_max: 600 * 5,
                modify_interval_value: 0,
                modify_interval_percent: 0,
                interval_id: null,
            }
        },
        mounted () {
            const self = this;
            // ポーリングでaxios定期実行
            this.interval_id = setInterval((function getTrain() {
                // // 更新インターバルが上限まで達したら更新インターバル初期化
                if (self.modify_interval_value >= self.modify_interval_max) {
                    self.modify_interval_value = 0;
                }

                // 更新インターバルが0ならAPI実行
                if (self.modify_interval_value === 0) {
                    // 遅延路線情報取得
                    axios.get('/sc-dashboard/public/api/train').then(response => {
                        self.railway_lines = response.data;
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
                self.modify_interval_percent = (self.modify_interval_value / self.modify_interval_max) * tenth_seconds;

                // 初回時の実行
                return getTrain;
            }()), 100);
        },
        beforeDestroy () {
            // ページ遷移時にポーリング解除
            clearInterval(this.interval_id);
        },
    }
</script>

<style lang="scss" scoped>
    $true-color: #ffb8b8;
    $false-color: #aaa;

    .railway-lines {
        height: 100%;
        .is-delay {
            text-align: center;
            font-size: 2rem;
            .true {
                color: $true-color;
            }
            .false {
                color: $false-color;
            }
        }
    }
</style>