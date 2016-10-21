<?php
if (isset($_POST[period])&&isset($_POST[mark])){

    getAll();
}else{
    index();
}


function index()
{
   $db= getDB();
    $data = array();
    $lastMonth = array();
    $priviousMonth = array();
    $statement = $db->query('select f.mark,count(*) as countMarks from feedback as f 
join oc_order as o
on o.order_id=f.order_id
where datediff(now(), o.date_added)<30
and o.order_status_id=5
group by f.mark
');
    while ($row = $statement->fetch_assoc()) {
        $lastMonth[$row[mark]] = $row[countMarks];
    }
    $statement = $db->query('select f.mark,count(*) as countMarks from feedback as f 
join oc_order as o
on o.order_id=f.order_id
where datediff(now(), o.date_added)>30
and datediff(now(), o.date_added)<60
and o.order_status_id=5
group by f.mark
');
    while ($row = $statement->fetch_assoc()) {
        $priviousMonth[$row[mark]] = $row[countMarks];
    }
    $data[last] = $lastMonth;
    $data[privios] = $priviousMonth;

    $_SESSION[data] = $data;
    renderView();
}

function  getAll(){
    $db= getDB();

    $dataArr = array();
    if($_POST[period] =='previous') {
        $statement = $db->query('select concat(c.firstname," ",c.lastname)  as name,c.email,o.order_id,f.notes,f.mark from feedback as f
join oc_customer as c 
on c.customer_id= f.customer_id
join oc_order as o 
on o.order_id=f.order_id
where datediff(now(), o.date_added)>30
and datediff(now(), o.date_added)<60
and o.order_status_id=5
and f.mark='.$_POST[mark]);

     //   echo(json_encode($_POST[period]));

        while ($row = $statement->fetch_assoc()) {
            array_push($dataArr,$row);

        }
    echo(json_encode($dataArr));
    } else{
        $statement = $db->query('select concat(c.firstname," ",c.lastname)  as name,c.email,o.order_id,f.notes,f.mark from feedback as f
join oc_customer as c 
on c.customer_id= f.customer_id
join oc_order as o 
on o.order_id=f.order_id
where datediff(now(), o.date_added)<30
and o.order_status_id=5
and f.mark='.$_POST[mark]);

        while ($row = $statement->fetch_assoc()) {
            array_push($dataArr,$row);
        }
        echo(json_encode($dataArr));
    }
}
function renderView()
{
    ob_start();
    $viewFileName = 'index.php';
    include($viewFileName);
    $htmlFromView = ob_get_contents();
    ob_end_clean();
    echo($htmlFromView);
}
function getDB(){
    $db = new mysqli('localhost', 'root', '', 'vimaxspa_vimax');
    $db->set_charset("utf8");
    if ($db->connect_errno) {
        die('Cannot connect to database');
    }
    return $db;
}