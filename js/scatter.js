var MYData = new Array();

var chart = new CanvasJS.Chart("chartContainer", {
    zoomEnabled: true,
    zoomType: "x",
    title: {
        text: ""
    },
    axisX: {
        viewportMinimum: -1,
        viewportMaximum: 1,
        includeZero: true,
        title: "正反向程度分數",
        maximum: 5,
        minimum: -5,
        gridThickness: 1,
        gridDashType: "dot",
        stripLines: [
            {
                value: 0,
                thickness: 4,
                color: "black",
                label: "反向                        正向",
                labelPlacement: "outside", //"outside"
                labelBackgroundColor: "white",
                labelFontColor: "black",
                labelMaxWidth: 400
            },
            {
                startValue: -999,
                endValue: 0,
                color: "lightblue",
                opacity: .1
            },
            {
                startValue: 0,
                endValue: 999,
                color: "pink",
                opacity: .1
            }
        ],
        crosshair: {
            enabled: true,
            color: "red"
        },
        labelFormatter: function(e) {
            return "";
        }
    },
    axisY: {
        reversed: true,
        title: "網頁來源",
        viewportMinimum: 0,
        viewportMaximum: 8,
        labelFormatter: function(e) {
            if (e.value == 1)
                return '醫療機構';
            else if (e.value == 2)
                return "政府研究單位";
            else if (e.value == 3)
                return "基金會";
            else if (e.value == 4)
                return "大專院校";
            else if (e.value == 5)
                return "新聞媒體";
            else if (e.value == 6)
                return "雜誌";
            else if (e.value == 7)
                return "其他";
            else
                return "";
        }
    },
    data: [{
        type: "scatter",
        color: "orange",
        markerSize: 20,
        toolTipContent: "<b>標題：{title}</b><br/><b>分數: </b>{x}",
        click: onClick,
        // color: "purple",
        dataPoints: MYData
    }]
});


function onClick(e) {
    location.href = e.dataPoint.link;
};
chart.render();

function queryString ()
{
    var query_string = {};
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++)
    {
        var pair = vars[i].split("=");
        // If first entry with this name
        if (typeof query_string[pair[0]] === "undefined")
        {
            query_string[pair[0]] = pair[1];
            // If second entry with this name
        } else if (typeof query_string[pair[0]] === "string")
        {
            var arr = [query_string[pair[0]], pair[1]];
            query_string[pair[0]] = arr;
            // If third or later entry with this name
        } else
        {
            query_string[pair[0]].push(pair[1]);
        }
    }
    return query_string;
}
let last = 0;
function GetElement(id, title, score, whitelist)
{
    var ret = new Object();
    ret["y"] = (parseInt(whitelist) == 0) ? 7 : parseInt(whitelist);
    ret["x"] = parseFloat(score);
    ret["link"] = "article.php?id=" + id;
    ret["title"] = title;
    return ret;
}

function IntervalGetChartResult()
{
    let s = queryString();
    s.last = last;
    $.get("/api/scatter.php", s, function (data) {
        let result = JSON.parse(data);
        // console.log(result);
        chart.title.set("text", result["title"]);
        result["result"].forEach(element => {
            MYData.push(GetElement(element["SearchResultId"], element["Title"], element["SearchResultRate"], element["WhiteListClass"]));
            last = element["SearchResultId"];
        });
    }).then(()=>{
        // console.log(MYData);
        chart.render();
    });
}

IntervalGetChartResult();
// interval_num = setInterval(IntervalGetChartResult, 3000);
