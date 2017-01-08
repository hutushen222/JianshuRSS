JianshuRSS
==========

Jianshu RSS Feed Generator

## Change Logs

### New (2017.01.08)

- 首页 (http://www.jianshu.com/) `/feeds/homepage`
- **推荐**
  - 新上榜 (http://www.jianshu.com/recommendations/notes?category_id=56) `/feeds/recommendations/notes[/latest]`
  - 日报 (http://www.jianshu.com/recommendations/notes?category_id=60) `/feeds/recommendations/notes/daily`
- **热门**
    - 7 日热门 (http://www.jianshu.com/trending/weekly) `/feeds/trending/weekly`
    - 30 日热门 (http://www.jianshu.com/trending/monthly) `/feeds/trending/monthly`
- 专题 (http://www.jianshu.com/c/xYuZYD) `/feeds/collections/:id`
- 作者 (http://www.jianshu.com/u/yZq3ZV) `/feeds/users/:id`
- 文集 (http://www.jianshu.com/nb/1294135) `/feeds/notebooks/:id`


### Old (2017.01.08)

- 最新 (`/feeds/latest/notes`)
- 推荐 (`/feeds/recommendations/notes`)
- 专题 (`/feeds/collections/:id`)
- 作者 (`/feeds/users/:id`)
- 文集 (`/feeds/notebooks/:id`)

-EOF-
