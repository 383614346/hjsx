
var jsPaging_ShowIndexNumber = 10; //显示分页跳转链接一共显示的按钮数量



/*-----------生成分页字符-------------*/
function jsPaging_CreatePage(pageContainerId,dataCount,pageSize,pageIndex,getDataMethodName) {
    dataCount = parseInt(dataCount);
    pageSize = parseInt(pageSize);
    pageIndex = parseInt(pageIndex);
    //如果数据小于每页显示条数就不显示分页控件
    if (dataCount < pageSize)
        return;
    //总页数
    var pageCount = Math.ceil(dataCount / pageSize);


    var strPage ;

    //页码大于最大页数
    if (pageIndex >= pageCount) {
        pageIndex = pageCount;
    }
    //页码小于1
    if (pageIndex < 1) {
        pageIndex = 1;
    }
    //计算上一页数据
    var upNum;
    if (pageIndex > 1) {
        upNum = pageIndex - 1;
    } else {
        upNum = 1;
    }

    //计算下一页数据
    var downNum ;


    if (pageIndex < pageCount) {
        downNum = pageIndex + 1;
    }
    else {
        downNum = pageCount;
    }

    strPage = '';

    //首页
    //strPage += "<a  href=\"javascript:" + getDataMethodName + "(1,"+ pageSize +")\">首页</a>";
    //上一页
    strPage += '<a class="pprev" href="javascript:' + getDataMethodName + '(' + upNum + ',' + pageSize + ')">&nbsp;&nbsp;</a>';
    //显示索引页
    //判断开始显示的索引
    var startIndex = jsPaging_GetPagingControlIndexStartIndex(pageCount,pageIndex );


    for (var i = 0; i < pageCount; i++) {
        //显示按钮的数量
        if (i >= jsPaging_ShowIndexNumber)
            break;
        //是否是最后一个按钮
        if (startIndex > pageCount)
            break;
        //当前页
        if (startIndex == pageIndex)
            strPage += '<a  class="anum act">' + startIndex + '</a>';
        else
            strPage += "<a class=\"anum\" href=\"javascript:" + getDataMethodName + "(" + startIndex + "," + pageSize + ")\">" + startIndex + "</a>";

        //设置跳转的页面	
        startIndex++;

    }

    //下一页
    strPage += "<a class=\"pnext\" href=\"javascript:" + getDataMethodName + "(" + downNum + "," + pageSize + ")\">&nbsp;&nbsp;</a>";
    //尾页
    //strPage += "<a  href=\"javascript:" + getDataMethodName + "(" + pageCount + "," + pageSize + ")\">末页</a>";

    strPage += '';
    //	var jsPaging_PageIndex = 1; //当前第几页
    //var jsPaging_PageSize = 5; //每页显示多少条数据
    //var jsPaging_DataCount = 0; //一共有多少数据
    //var jsPaging_PageCount = 1; //总页数
    //var jsPaging_ShowIndexNumber = 5; //显示分页跳转链接一共显示的按钮数量







    $("#" + pageContainerId).html(strPage);
}

//获取分页控件索引的开始位置
function jsPaging_GetPagingControlIndexStartIndex(pageCount, pageIndex) {
    var startIndex = 1;
    if (jsPaging_ShowIndexNumber >= pageCount) {
        startIndex = 1;
    }
    else if (jsPaging_ShowIndexNumber <= pageCount) {
        var tempIndex = pageIndex - Math.ceil(jsPaging_ShowIndexNumber / 2);
        if (tempIndex > 1) {
            startIndex = tempIndex;

        }
        else {
            startIndex = 1;
        }

    }
    return startIndex;
}