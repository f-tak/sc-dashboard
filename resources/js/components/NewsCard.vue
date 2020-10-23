<template>
    <div class="card" id="news">
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
                <div class="col-6">News</div>
                <div class="col-6 text-right">
                    Last modified : {{ last_modified }}
                </div>
            </div>

        </div>
        <div class="card-body">
            <div class="h-100 overflow-hidden">
                <div class="news-item h-100" :class="{'d-block': index === display_news_index }" v-for="(news, index) in news_list">
                    <div class="title"><a :href="news.link">{{ news.title }}</a></div>
                    <div class="date">Published : {{ news.date }}</div>
                    <div class="description">{{ news.description }}</div>
                </div>
            </div>
            <div class="card-footer">
                <k-progress
                        status="success"
                        type="line"
                        :percent="change_interval_percent"
                        :line-height="3"
                        active
                        color="#3a8fc2"
                        :show-text="false">
                </k-progress>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                news_list: null,
                last_modified: null,
                modify_interval_max: 600 * 10,
                modify_interval_value: 0,
                modify_interval_percent: 0,
                change_interval_max: 600,
                change_interval_value: 0,
                change_interval_percent: 0,
                display_news_index: 0,
                interval_id: null,
            }
        },
        mounted () {
            const self = this;

            // ポーリングでaxios定期実行
            this.interval_id = setInterval((function getNews() {
                // // 表示切替インターバルが上限まで達したら表示切替インターバル初期化
                if (self.change_interval_value >= self.change_interval_max) {
                    self.change_interval_value = 0;
                }

                // 表示切替インターバルが0ならニュース表示を次へ切り替え
                if (self.change_interval_value === 0 && self.news_list !== null) {
                    // 表示するニュースのインデックス
                    self.display_news_index++;
                    if (self.display_news_index >= self.news_list.length) {
                        // 表示するニュースのインデックスを超えた場合は最初に戻る
                        self.display_news_index = 0;
                    }
                }

                // // 更新インターバルが上限まで達したら更新インターバル初期化
                if (self.modify_interval_value >= self.modify_interval_max) {
                    self.modify_interval_value = 0;
                }

                // 更新インターバルが0ならAPI実行
                if (self.modify_interval_value === 0) {
                    // ニュース取得
                    axios.get('/api/news').then(response => {
                        self.news_list = response.data;
                    }).catch(err => {
                    }).finally({
                    });
                    // 最終更新日時
                    let date = new Date();
                    let year = date.getFullYear();
                    let month = date.getMonth() + 1;
                    let day = date.getDate();
                    self.last_modified = year + '/' + month + '/' + day + ' ' + date.toLocaleTimeString();

                    // 表示切替を初期化
                    self.display_news_index = 0;
                    self.change_interval_value = 0;
                }


                // 表示切替インターバルを進める
                self.change_interval_value += 1;
                // 表示切替インターバルのパーセント計算
                self.change_interval_percent = (self.change_interval_value / self.change_interval_max) * 100;

                // 更新インターバルを進める
                self.modify_interval_value += 1;
                // 更新インターバルのパーセント計算
                self.modify_interval_percent = (self.modify_interval_value / self.modify_interval_max) * 100;

                // 初回時の実行
                return getNews;
            }()), 100);
        },
        beforeDestroy () {
            // ページ遷移時にポーリング解除
            clearInterval(this.interval_id);
        },
    }
</script>


<style lang="scss" scoped>

    .news-item {
        display: none;
        .title {
            font-size: 2vw;
        }
        .date {
            text-align: right;
        }
        .description {
            font-size: 1.5vw;
        }
    }
</style>
