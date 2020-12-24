document.title = "狀態 - 網路上正反意向健康保健資訊之推薦";
let taskQueue = new countUp.CountUp('taskQueue', 0);
let searchQueue = new countUp.CountUp('searchQueue', 0);
let waitTime = new countUp.CountUp('waitTime', 0, {suffix: ' min'});
taskQueue.start()
searchQueue.start()
waitTime.start()

function getTime(search, task) {
    let sec = task * 15 + search * 60 * 5;
    return Math.round(sec / 60);
}

function getData() {
    $.get("/api/status.php", function (data) {
        let result = JSON.parse(data);
        console.log(result);
        let task = result["task_queue"];
        let search = result["search_queue"]
        taskQueue.update(task);
        searchQueue.update(search);
        waitTime.update(getTime(search, task))
    });
}

getData()
setInterval(getData, 5000);