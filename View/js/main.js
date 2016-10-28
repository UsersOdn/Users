/**
 * Created by hedi on 10/5/16.
 */

//______________________________________________________________________________ login page


/*********  login *********/


function Login() {

    var valid;
    valid = validateLoginForm();
    if(valid) {

        $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
                act: $("#act").val(),
                username: $("#username").val(),
                password: CryptoJS.MD5( $("#password").val() ).toString(),
                captcha: $("#captcha").val()
            } , function (data) {
                var obj = jQuery.parseJSON(data);

                if (obj['act'] == 'data')
                    Redirect('/View/pages/temp.html');

                else if (obj['act'] == 'message') {
                    prepare_message( obj['text'] , 'fa' , obj['title'] );
                }
            }).fail(function(){alert();})
        );
    }

}

//______________________________________ load user access
function loadUserAccess() {
    var obj = null;

        $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
                act:'get_user_access'
            })
        ).done(function (data) {

                 obj = jQuery.parseJSON(data);

                if (obj['act'] == 'data') {
                    var acts = obj['data'];
                    //console.log("actions: "+acts[0]['Actions']);
                   // setvars( acts[0]['Actions'] );
                }

                else if (obj['act'] == 'message') {
                    showMessageModal('warning', obj['title'], obj['text']);
                    return null;
                }
            }
        );

		
}
//________________________________ load profile page
function loadProfilePage() {
 //   alert("profile");
    var obj = null;

    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act:'show_user_profile'
        })
    ).done(function (data) {


        //console.log( data );
            obj = jQuery.parseJSON(data);

            if (obj['act'] == 'data') {
                var aray = obj['data'];
                var name= (aray['FirstName'] == undefined ? '' : aray['FirstName']) +" "+(aray['LastName'] == undefined ? '' : aray['LastName']);
                document.getElementById("hideId").value=aray['UserId'];
                window.myid=aray['UserId'];
                setvars2( name , 'profilenames' );
               //showpopupModal('info', obj['title'], name);
            }

            else if (obj['act'] == 'message') {
                alert("ok");

                prepare_message(obj['text'], 'fa' , obj['title']);
               // showLoginModal('warning', obj['title'], obj['text']);
                return null;
            }
        }
    );


}

//_____________________________________ prepare message
function prepare_message(msg , lang , msgt) {
    $.when($.get("/View/lang/lang.xml" , function (xml) {
        var xmlDoc = xml;

        var x = xmlDoc.getElementsByTagName(msg); // get all msg tags as array, and x = first msg tag

        //console.log(x);
        if( x.length == 0 )
        {

            show_modal( msgt , msg  , 'fa');
            return;
        }
        var persianMsg = x[0].firstElementChild.innerHTML; // get persian data of a message
        var englishMsg = x[0].firstElementChild.nextElementSibling.innerHTML; // set y = x's child node
        var type = x[0].lastElementChild.innerHTML;

        //alert(type);

        switch (lang )
        {
            case 'fa':
                show_modal( msgt , persianMsg , type );

                break;
            case 'en':
                show_modal( msgt , englishMsg , type);

                break;
            default:
                show_modal( msgt , msg  , type );
                break;
        }


    }));

}


var d = null;
//____________________________________length3d
function length3d(obj){
    var c = 0;
    for (var key in obj) {
    if (obj.hasOwnProperty(key)) ++c;
    }
    return c;
}

//________________________________ show modal
function show_modal(title , value , type )
{
    //alert(type);
    var msgtype = 'info';
    switch ( type )
    {
        case 'Err':
        case 'err':
            msgtype = 'danger';
            break;
        case 'info':
        case 'msg':
            msgtype = 'info';
            break;
        case 'suc':
            msgtype = 'success';
            break;
        case 'wrn':
        default:
            msgtype = 'warning';
            break;


    }
    showMessageModal( msgtype , title , value );
    //console.log( msgtype + title + value );

}

//____________________________load all remaining groups in select tag
function loadAllRemainingGroups(uid){
    var selectgroup = document.getElementById("selectgroup");
    selectgroup.innerHTML = '';
    var option = document.createElement("option");
    option.text = "گروه دسترسی";
    selectgroup.add(option);

    // reload regions
    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act: 'get_all_remaining_groups',
            id: uid
        }, function (data) {
            console.log(data);
            var obj = jQuery.parseJSON(data);

            //console.log(obj['data']);
            if (obj['act'] == 'data'){

                var groupcounter =obj['data'].length;
                var x = document.getElementById("selectgroup");
                for(var c=0;c<groupcounter;c++){
                    var option = document.createElement("option");
                    option.text = obj['data'][c]['GroupName'];
                    option.value= obj['data'][c]['id'];
                    x.add(option);
                }

            }


            else if (obj['act'] == 'message') {
               // prepare_message( obj['text'] , 'fa' , obj['title'] );
            }
        })
    );


}
//____________________________load all remaining roles in select tag
function loadAllRemainingRoles(uid){
    var selectrole = document.getElementById("selectrole");
    selectrole.innerHTML = '';
    var option = document.createElement("option");
    option.text = "دسترسی";
    selectrole.add(option);

    // reload regions
    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act: 'get_all_remaining_roles',
            id: uid
        }, function (data) {
            console.log("remaining role="+data);
            var obj = jQuery.parseJSON(data);

            //console.log(obj['data']);
            if (obj['act'] == 'data'){

                var rolecounter =obj['data'].length;
                var x = document.getElementById("selectrole");
                for(var c=0;c<rolecounter;c++){
                    var option = document.createElement("option");
                    option.text = obj['data'][c]['Name'];
                    option.value= obj['data'][c]['id'];
                    x.add(option);
                }

            }


            else if (obj['act'] == 'message') {
                // prepare_message( obj['text'] , 'fa' , obj['title'] );
            }
        })
    );

}

//____________________________load all remaining roles in select tag
function loadAllRemainingRoleForGroup(gid){
    var selectrole = document.getElementById("selectroleforgroup");
    selectrole.innerHTML = '';
    var option = document.createElement("option");
    option.text = "دسترسی";
    selectrole.add(option);

    // reload regions
    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act: 'get_all_remaining_roles_for_group',
            id: gid
        }, function (data) {
            console.log("remaining role="+data);
            var obj = jQuery.parseJSON(data);

            //console.log(obj['data']);
            if (obj['act'] == 'data'){

                var rolecounter =obj['data'].length;
                var x = document.getElementById("selectroleforgroup");
                for(var c=0;c<rolecounter;c++){
                    var option = document.createElement("option");
                    option.text = obj['data'][c]['Name'];
                    option.value= obj['data'][c]['id'];
                    option.data=obj['data'][c]['Description'];
                    x.add(option);
                }

            }


            else if (obj['act'] == 'message') {
                // prepare_message( obj['text'] , 'fa' , obj['title'] );
            }
        })
    );

}


//____________________________load all remaining actions in select tag
function loadAllRemainingActions(rid){
    var selectaction = document.getElementById("selectaction");
    selectaction.innerHTML = '';
    var option = document.createElement("option");
    option.text = "اکشن";
    option.data="";
    selectaction.add(option);

    // reload regions
    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act: 'get_all_remaining_actions',
            id: rid
        }, function (data) {
            console.log("remaining action="+data);

        var obj = jQuery.parseJSON(data);

            //console.log(obj['data']);
            if (obj['act'] == 'data'){

                var actioncounter =obj['data'].length;
                var x = document.getElementById("selectaction");
                for(var c=0;c<actioncounter;c++){
                    var option = document.createElement("option");
                    option.text = obj['data'][c]['Description'];
                    option.value= obj['data'][c]['id'];
                    option.data=obj['data'][c]['ActionName'];
                    x.add(option);
                }

            }


            else if (obj['act'] == 'message') {
                // prepare_message( obj['text'] , 'fa' , obj['title'] );
            }
        })
    );

}
//____________________________ main page

function loadMainPage() {

    var obj = null;

    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act:'get_users'
        })
    ).done(function (data) {
        //console.log("&&&&&&&&&&&&&"+data);

            obj = jQuery.parseJSON(data);
            //console.log("************"+keys);




        var el = document.getElementById('maintable_wrapper');
        if(el!=undefined){
            el.parentElement.removeChild(el);//innerHTML="secondtable_wrapper";
            document.getElementById("returnbutton").innerHTML="";

        }


        var tableplace="mytable";
        var tableid= "maintable";
        var tablebodiid="gridview";
        var havereport=true;
        var haveoperation=true;
        var header = ["ردیف", "نام", "نام خانوادگی", "شماره همراه", "ایمیل", "گزارشات", "عملیات اجرایی"];
        var footer = ["ردیف", "نام", "نام خانوادگی", "شماره همراه", "ایمیل", "گزارشات", "عملیات اجرایی"];
        var btnr0= {type:"button", value:"کسب و کار", class:"btn btn-xs btn-success", function:"default",modal:"data-toggle='modal' data-target='#mainModal'"};
        var btnr1= {type:"button", value:"پروفایل", class:"btn btn-xs btn-warning", function:"showProfile",modal:"data-toggle='modal' data-target='#mainModal'"};
        var btnr2= {type:"button", value:"باشگاه", class:"btn btn-xs btn-primary", function:"default",modal:"data-toggle='modal' data-target='#mainModal'"};
        var btno0= {type:"button", value:"حذف", class:"btn btn-xs btn-danger",triger:"deleteUser" ,function:"prepareConfirmModal",modal:"data-toggle='modal' data-target='#confirmModal'"};
        var btno1= {type:"button", value:"دسترسی", class:"btn btn-xs btn-warning", function:"loadRole",modal:"data-toggle='modal' data-target='#mainModal'"};
        var btno2= {type:"button", value:"گروه دسترسی", class:"btn btn-xs btn-info",function:"loadGroup",modal:"data-toggle='modal' data-target='#mainModal'"};
        var btno3= {type:"button", value:"فعال سازی", class:"btn btn-xs btn-default", function:"userActivation",modal:"data-toggle='modal' data-target='#messageModal'"};
        var btno4= {type:"button", value:"ورود", class:"btn btn-xs btn-primary", function:"default",modal:"data-toggle='modal' data-target='#mainModal'"};
        var reportbtn={0:btnr0 , 1:btnr1 ,2:btnr2};
        var operationbtn={0:btno0 , 1:btno1 ,2:btno2,3:btno3,4:btno4};
        var params=null;

        if (obj['act'] == 'data') {

                //var keys = Object.keys(obj['data'][0]);
                //var acts = obj['data'];
                /*
                 create parameters for create table include:( tableplace , tableid, tablecontentid,arrays for header,array of footer,tablebody,havereport,reportbtn,haveoperation,operationbtn)
                 */


                tableCreator2(tableplace , tableid , tablebodiid , header , obj['data'] , footer , havereport , reportbtn , haveoperation , operationbtn,params);


            }

            else if (obj['act'] == 'message') {
                prepare_message(obj['text'], 'fa' , obj['title'] );
            tableCreator2(tableplace , tableid , tablebodiid , header , null , footer , havereport , reportbtn , haveoperation , operationbtn,params);

                return null;
            }
        }
    );


}

//______________________ main table creator
function  tableCreator2(tableplace,tablename , tablebodiname , tableheader , tablebody , tablefooter,havereport,reportbtn,haveoperation,operationbtn,param) {


    // console.log( param + "111");
    document.getElementById(tableplace).innerHTML=document.getElementById(tableplace).innerHTML+'<table id="'+tablename+'" class="table table-responsive table-bordered table-striped">';
    /*
     create table header
     */
    var str = '<thead>'+
        '<tr>';
    for(var x1=0;x1<tableheader.length;x1++){
        str += '<th>'+tableheader[x1]+'</th>';
    }
    str += '</tr>' + '</thead>';
    document.getElementById(tablename).innerHTML += str;
    /*
     create table body
     */
    document.getElementById(tablename).innerHTML +='<tbody id="'+tablebodiname+'">'+ '</tbody>';
    document.getElementById(tablename).innerHTML +='<tfoot>'+'<tr>';

    /*
     create table footer
     */
    str = '';
    for(var x2=0;x2<tablefooter.length;x2++){
        str += '<th>'+tablefooter[x2]+'</th>';
    }
    document.getElementById(tablename).innerHTML += str;
    document.getElementById(tablename).innerHTML += '</tfoot>'+'</table>';

    /*
     fill table body
     */

    //var table = $('#maintable').DataTable();
    var table =$('#'+tablename).DataTable( {
        dom: 'Bfrtip',
        buttons:  [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [ 0, ':visible' ]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'csv',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: ':visible'
                }
            },
            'colvis'

        ]

    } );
    try {
        if(tablebody ==null ){
            table.draw();
            return;
        }
        var keys = Object.keys(tablebody[0]);
        var totalcolumn=keys.length;
    }
    catch(err) {
    }
    var rows = tablebody.length;

    //console.log("keys:****************"+keys);

    for(var i=0;i<rows;i++){
        //initializing
        var coulmn=0;
        var j=i+1;

        //create btn
        if(havereport){
            var reportcolumn='<td>'+'<div class="btn-group btn-group-xs row">';
            for(var z1=0;z1<length3d(reportbtn);z1++){
                var type1=reportbtn[z1]['type'];
                var color1=reportbtn[z1]['class'];
                var onclk1=reportbtn[z1]['function'];


                if(onclk1!="prepareConfirmModal"){
                    var parameters1="(";
                    if(param!=null){
                        for(var g=0;g<param.length;g++){
                            parameters1+=param[g]+",";
                        }
                    }
                    parameters1+=tablebody[i][keys[coulmn]];
                    parameters1+=")";
                    //console.log("param="+parameters);
                    var func1= onclk1+parameters1;
                    //console.log("func1="+func1);

                }
                else{
                    var arg1;
                    // alert('confirm else');
                    var triger1=operationbtn[z1]['triger'];
                    if(param!=null){
                        arg1=param.slice();
                        arg1.push(tablebody[i][keys[coulmn]]);
                    }
                    else{
                        arg1=[tablebody[i][keys[coulmn]]];
                    }
                    func1=onclk1+"("+arg1+",'"+ triger1 +"')";
                }
                //console.log("func1="+func1);
                var mdl1=reportbtn[z1]['modal'];
                //console.log("mdl:"+mdl1);
                var val1=reportbtn[z1]['value'];
                reportcolumn +='<input  type="'+type1+'" class="'+color1+ '" onclick="'+func1+ '"   value="'+val1+'"   ' + mdl1+ ' >';
            }
            reportcolumn+='</div>'+'</td>';
        }
        if(haveoperation){
            var operationcolumn='<td>'+'<div class="btn-group btn-group-xs row">';
            for(var z2=0;z2<length3d(operationbtn);z2++){
                var type2=operationbtn[z2]['type'];
                var color2=operationbtn[z2]['class'];
                var onclk2=operationbtn[z2]['function'];
                if(onclk2!="prepareConfirmModal"){
                    var parameters2="(";
                    if(param!=null){
                        for(var g=0;g<param.length;g++){
                            parameters2+=param[g]+",";
                        }
                    }
                    parameters2+=tablebody[i][keys[coulmn]];
                    parameters2+=")";
                    //console.log("param="+parameters);
                    var func2= onclk2+parameters2;
                    //console.log("func2="+func2);
                }
                else{
                    //alert('confirm else2');
                    // console.log(param);
                    var arg2 = null;
                    var triger2=operationbtn[z2]['triger'];
                    if(param!=null){
                        arg2=param.slice();
                        //console.log("arg and param:"+arg2+"   "+param);
                        arg2.push(tablebody[i][keys[coulmn]]);
                    }
                    else{
                        arg2=[tablebody[i][keys[coulmn]]];
                    }

                    func2=onclk2+"("+"["+arg2+"]"+",'"+triger2+"')";
                    //console.log("func2="+func2);
                }
                //var func2= onclk2+"("+tablebody[i][keys[coulmn]]+")";
                var mdl2=operationbtn[z2]['modal'];
                var val2=operationbtn[z2]['value'];
                operationcolumn +='<input type="'+type2+'" class="'+color2+ '" onclick="'+func2+'" '+mdl2+' value="'+val2+'" >';
            }
            operationcolumn+='</div>'+'</td>';
            //console.log("column"+"="+operationcolumn);
        }

        //console.log("tablebody="+tablebody[i]);

        //create data row in table
        var rowstring='<tr>'+'<td>'+j+'<input id="hide'+i+'" type="hidden" style="display: none;" value="'+tablebody[i][keys[coulmn++]]+'"></td>';
        for(var clnum=1;clnum<totalcolumn;clnum++ ){
            var s='<td>'+tablebody[i][keys[coulmn++]]+'</td>';
            rowstring+=s;
        }
        if(havereport){
            rowstring+=reportcolumn;
        }
        if(haveoperation){
            rowstring+=operationcolumn;
        }
        rowstring+='</td>';
        //console.log(rowstring);


        table.row.add($( rowstring
            // '<tr>'+
            // '<td>'+j+'<input id="hide'+i+'" type="hidden" style="display: none;" value="'+tablebody[i][keys[coulmn++]]+'"></td>'+
            // '<td>'+tablebody[i][keys[coulmn++]]+'</td>'+
            // '<td>'+tablebody[i][keys[coulmn++]]+'</td>'+
            //'<td></td>'+
            // '<td>'+tablebody[i][keys[coulmn++]]+'</td>'+
            //'<td>'+tablebody[i][keys[coulmn++]]+'</td>'+
            //   reportcolumn+
            // '</div>'+
            //'</td>'+
            //operationcolumn+
            //'</tr>'
        )).draw(true);//end of create data row

        table.columns.adjust().draw();
    }

}

/****************************
 * load states function
 */
function loadStates(){
    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act: 'get_all_states'
        }, function (data) {
            var obj = jQuery.parseJSON(data);
            //console.log(obj['data']);
            //console.log(obj['data']);
            if (obj['act'] == 'data'){
                var statecounter =obj['data'].length;
                var x = document.getElementById("state");
                //x.innerHTML = '';
                x.innerHTML = '';
                var option = document.createElement("option");
                option.text = "استان";
                option.value = "0";
                x.add(option);
                for(var c = 0 ; c < statecounter ; c++ )  {
                    var option = document.createElement("option");
                    option.text = obj['data'][c]['StateName'];
                    option.value=obj['data'][c]['id'];
                    x.add(option);
                }

            }


            else if (obj['act'] == 'message') {
                prepare_message( obj['text'] , 'fa' , obj['title'] );
            }
        })
    );

}
/****************************
 * load cities function
 */
function loadCities(stateid , cityid){

    // reset city select
    var cityselect = document.getElementById("city");
    cityselect.innerHTML = '';
    var option = document.createElement("option");
    option.text = "شهر";
    option.value = "0";
    cityselect.add(option);

    //reset region tag
    var regionselect = document.getElementById("region");
    regionselect.innerHTML = '';
    var option = document.createElement("option");
    option.text = "منطقه";
    option.value = "0";
    regionselect.add(option);


    // load new city list
    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act: 'get_all_cities_by_state_id',
            id: stateid
        }, function (data) {
            //console.log(data);
            /*
             delete city select
             */
            var obj = jQuery.parseJSON(data);

            console.log(obj['data']);
            if (obj['act'] == 'data'){

                var citycounter =obj['data'].length;
                var x = document.getElementById("city");
                for(var c=0;c<citycounter;c++){
                    var option = document.createElement("option");
                    option.text = obj['data'][c]['CityName'];
                    option.value= obj['data'][c]['id'];
                    x.add(option);
                }

                if( stateid > 0 )
                {

                    if(cityid!=null){
                        console.log("cityid====="+cityid);
                        var cityelement = document.getElementById('city');
                        //loadCities( state );
                        //wait(2000);
                        //element = document.getElementById('city');
                        //element.value = city;
                        //console.log( 'city :'+ city );
                        option = $('#city option[value="'+cityid+'"]');
                        cityelement.selectedIndex = option.index();

                        console.log( 'cityelement :'+ cityelement );
                        console.log('city:'+city);
                    }

                }




            }


            else if (obj['act'] == 'message') {
                if( stateid != 0 )
                    prepare_message( obj['text'] , 'fa' , obj['title'] );
            }
        })
    );

}
/****************************
 * load regions function
 */
function loadRegions(cityid , regionid){


    //reset region tag
    var regionselect = document.getElementById("region");
    regionselect.innerHTML = '';
    var option = document.createElement("option");
    option.text = "منطقه";
    option.value = "0";
    regionselect.add(option);

    // reload regions
    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act: 'get_all_regions_by_city_id',
            id: cityid
        }, function (data) {
            //console.log(data);
            var obj = jQuery.parseJSON(data);
            if (obj['act'] == 'data'){

                var regioncounter =obj['data'].length;
                var x = document.getElementById("region");
                for(var c=0;c<regioncounter;c++){
                    var option = document.createElement("option");
                    option.text = obj['data'][c]['RegionName'];
                    option.value= obj['data'][c]['id'];
                    x.add(option);
                }



                if(regionid!=null){
                    option = $('#region option[value="'+regionid+'"]');
                    document.getElementById('region').selectedIndex = option.index();
                }



            }


            else if (obj['act'] == 'message') {
                if( cityid != 0 )
                    prepare_message( obj['text'] , 'fa' , obj['title'] );
            }
        })
    );

}
/******************************
 * load group function
 */
function loadRole(uid){

    document.getElementById('mainModalHeaderLabel').innerHTML='<h2>دسترسی</h2>'+ '<br>'+ '<br>';
    document.getElementById('mainModalContent').innerHTML='<div class="box box-info">'+
        '<div class="box-header with-border">'+
        '<h3 class="box-title">ایجاد دسترسی جدید</h3>'+
        '<br>'+'<br>'+
        '<div class="box-body">'+
        '<input type="button" id="allRoleCollapse" onclick="loadAllRemainingRoles('+uid+')" class="btn btn-dropbox btn-flat btn-info " value="ایجاد" data-toggle="collapse" data-target="#createCollapse">'+
        '<br>'+'<br>'+
        '<div id="createCollapse" class="collapse">'+
        '<select id="selectrole" class="col-md-2 selectpicker input-sm">'+
        '<option value="0">دسترسی</option>'+
        '</select>'+
        '<button class="btn btn-default" onclick="setUserRole('+uid+')">' +"تایید"+'</button>'+
        '<br>'+
        '<br>'+
        '<br>'+ '<br>'+
        '</div>'+
        '</div>'+
        '</div>'+
        '</div> ';




    var obj = null;
    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act:'get_user_role',
            id:uid
        })
    ).done(function (data) {
            //console.log("&&&&&&&&&&&&&"+data);

            obj = jQuery.parseJSON(data);
            //var keys = Object.keys(obj['data'][0]);
            //console.log("ZZZZZZZZZZZZZZZZZZZZZZZ"+obj['data']);
            //console.log("************"+keys);
        //console.log("data bbbbbbbbbbbbbbbbbbbarabar ast ba="+obj['data']);
        //reconsole.log("data bbbbbbbbbbbbbbbbbbbarabar ast ba="+obj['data']);
            if (obj['act'] == 'data') {

                var acts = obj['data'];
                /*
                 create parameters for create table include:( tableplace , tableid, tablecontentid,arrays for header,array of footer,tablebody,havereport,reportbtn,haveoperation,operationbtn)
                 */
                var tableplace="mainModalContent";
                var tableid= "secondtable";
                var tablebodiid="secondcontent";
                var havereport=false;
                var haveoperation=true;
                var header = ["ردیف", "نام دسترسی","عملیات اجرایی"];
                var footer = ["ردیف", "نام دسترسی","عملیات اجرایی"];
                var btno0= {type:"button", value:"حذف", class:"btn btn-xs btn-danger",triger:"deleteUserRole" , function:"prepareConfirmModal",modal:"data-toggle='modal' data-target='#confirmModal'"};
                var reportbtn=null;
                var operationbtn={0:btno0};
                var params=[uid];



                tableCreator2(tableplace , tableid , tablebodiid , header , obj['data'] , footer , havereport , reportbtn , haveoperation , operationbtn,params);



            }

            else if (obj['act'] == 'message') {
                prepare_message(obj['text'], 'fa' , obj['title'] );
                //showMessageModal('warning', obj['title'], obj['text']);
                return null;
            }
        }
    );



}



//////////////////////////////////
/******************************
 * load group function
 */
function loadGroup(uid){

    document.getElementById('mainModalHeaderLabel').innerHTML='<h2>گروه دسترسی</h2>'+ '<br>'+ '<br>';
    document.getElementById('mainModalContent').innerHTML='<div class="box box-info">'+
        '<div class="box-header with-border">'+
        '<h3 class="box-title">ایجاد گروه دسترسی جدید</h3>'+
        '<br>'+'<br>'+
        '<div class="box-body">'+
        '<input type="button" id="allGroupCollapse" onclick="loadAllRemainingGroups('+uid+')" class="btn btn-dropbox btn-flat btn-info " value="ایجاد" data-toggle="collapse" data-target="#createCollapse">'+
        '<br>'+'<br>'+
        '<div id="createCollapse" class="collapse">'+
        '<select id="selectgroup" class="col-md-2 selectpicker input-sm">'+
        '<option value="0">گروه دسترسی</option>'+
        '</select>'+
        '<button class="btn btn-default" onclick="setUserGroup('+uid+')">' +"تایید"+'</button>'+
        '<br>'+
        '<br>'+
        '<br>'+ '<br>'+
        '</div>'+
        '</div>'+
        '</div>'+
        '</div> ';




    var obj = null;
    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act:'get_user_group',
            id:uid
        })
    ).done(function (data) {
            //console.log("&&&&&&&&&&&&&"+data);

            obj = jQuery.parseJSON(data);
            //var keys = Object.keys(obj['data'][0]);
            //console.log("ZZZZZZZZZZZZZZZZZZZZZZZ"+obj['data']);
            //console.log("************"+keys);
            if (obj['act'] == 'data') {

                var acts = obj['data'];
                /*
                 create parameters for create table include:( tableplace , tableid, tablecontentid,arrays for header,array of footer,tablebody,havereport,reportbtn,haveoperation,operationbtn)
                 */
                var tableplace="mainModalContent";
                var tableid= "secondtable";
                var tablebodiid="secondcontent";
                var havereport=false;
                var haveoperation=true;
                var header = ["ردیف", "نام گروه دسترسی","عملیات اجرایی"];
                var footer = ["ردیف", "نام گروه دسترسی","عملیات اجرایی"];
                var btno0= {type:"button", value:"حذف", class:"btn btn-xs btn-danger",triger:"deleteUserGroup" , function:"prepareConfirmModal",modal:"data-toggle='modal' data-target='#confirmModal'"};
                var reportbtn=null;
                var operationbtn={0:btno0};
                var params=[uid];



                tableCreator2(tableplace , tableid , tablebodiid , header , obj['data'] , footer , havereport , reportbtn , haveoperation , operationbtn,params);

                /*
                 execute data table javascript file
                 */

                //$('#maintable').DataTable( {
                //   dom: 'Bfrtip',
                //   buttons: [
                //         'copy', 'csv', 'excel', 'pdf', 'print'
                //   ]
                // } );

            }

            else if (obj['act'] == 'message') {
                prepare_message(obj['text'], 'fa' , obj['title'] );
                //showMessageModal('warning', obj['title'], obj['text']);
                return null;
            }
        }
    );



}













/////////////////////////////////////////////load users that have special role

//////////////////////////////////
/******************************
 * load group function
 */
function loadUserwithSpecialRole(rid){

    document.getElementById('mainModalHeaderLabel').innerHTML='<h2>کاربران دارای این نقش</h2>'+ '<br>'+ '<br>';
    document.getElementById('mainModalContent').innerHTML='<div class="box box-info">'+
      //  '<div class="box-header with-border">'+
       // '<h3 class="box-title">افزودن کاربر جدید جدید</h3>'+
       // '<br>'+'<br>'+
      //  '<div class="box-body">'+
       // '<input type="button" id="allUserCollapse" onclick="loadAllRemainingGroups('+rid+')" class="btn btn-dropbox btn-flat btn-info " value="ایجاد" data-toggle="collapse" data-target="#createUserCollapse">'+
      //  '<br>'+'<br>'+
      //  '<div id="createUserCollapse" class="collapse">'+
      //  '<select id="selectuser" class="col-md-2 selectpicker input-sm">'+
       // '<option value="0">گروه دسترسی</option>'+
      //  '</select>'+
      //  '<button class="btn btn-default" onclick="setRoleUser('+rid+')">' +"تایید"+'</button>'+
      //  '<br>'+
      //  '<br>'+
      //  '<br>'+ '<br>'+
       // '</div>'+
      //  '</div>'+
       // '</div>'+
        '</div> ';




    var obj = null;
    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act:'get_all_users_have_this_role',
            id:rid
        })
    ).done(function (data) {
            console.log("&&&&&&&&&&&&&"+data);

            obj = jQuery.parseJSON(data);


        /*
         create parameters for create table include:( tableplace , tableid, tablecontentid,arrays for header,array of footer,tablebody,havereport,reportbtn,haveoperation,operationbtn)
         */
        var tableplace="mainModalContent";
        var tableid= "secondtable";
        var tablebodiid="secondcontent";
        var havereport=false;
        var haveoperation=false;
        var header = ["ردیف", "نام", "نام خانوادگی", "شماره همراه", "ایمیل"];
        var footer = ["ردیف", "نام", "نام خانوادگی", "شماره همراه", "ایمیل"];
        //var btnr0= {type:"button", value:"پروفایل", class:"btn btn-xs btn-warning", function:"showProfile",modal:"data-toggle='modal' data-target='#mainModal'"};
       // var reportbtn={0:btnr0};
        var reportbtn=null;
        var operationbtn=null;
        var params=[rid];

        //var keys = Object.keys(obj['data'][0]);
            //console.log("ZZZZZZZZZZZZZZZZZZZZZZZ"+obj['data']);
            //console.log("************"+keys);
            if (obj['act'] == 'data') {

                //var acts = obj['data'];
                /*
                 create parameters for create table include:( tableplace , tableid, tablecontentid,arrays for header,array of footer,tablebody,havereport,reportbtn,haveoperation,operationbtn)
                 */



                tableCreator2(tableplace , tableid , tablebodiid , header , obj['data'] , footer , havereport , reportbtn , haveoperation , operationbtn,params);

                /*
                 execute data table javascript file
                 */

                //$('#maintable').DataTable( {
                //   dom: 'Bfrtip',
                //   buttons: [
                //         'copy', 'csv', 'excel', 'pdf', 'print'
                //   ]
                // } );

            }

            else if (obj['act'] == 'message') {

                tableCreator2(tableplace , tableid , tablebodiid , header , null , footer , havereport , reportbtn , haveoperation , operationbtn,params);

                prepare_message(obj['text'], 'fa' , obj['title'] );
                //showMessageModal('warning', obj['title'], obj['text']);
                return null;
            }
        }
    );



}




/////////////////////////////////////////////load users that have special role

//////////////////////////////////
/******************************
 * load group function
 */
function loadUserwithSpecialGroup(gid){

    document.getElementById('mainModalHeaderLabel').innerHTML='<h2>کاربران دارای این گروه</h2>'+ '<br>'+ '<br>';
    document.getElementById('mainModalContent').innerHTML='<div class="box box-info">'+
        //  '<div class="box-header with-border">'+
        // '<h3 class="box-title">افزودن کاربر جدید جدید</h3>'+
        // '<br>'+'<br>'+
        //  '<div class="box-body">'+
        // '<input type="button" id="allUserCollapse" onclick="loadAllRemainingGroups('+rid+')" class="btn btn-dropbox btn-flat btn-info " value="ایجاد" data-toggle="collapse" data-target="#createUserCollapse">'+
        //  '<br>'+'<br>'+
        //  '<div id="createUserCollapse" class="collapse">'+
        //  '<select id="selectuser" class="col-md-2 selectpicker input-sm">'+
        // '<option value="0">گروه دسترسی</option>'+
        //  '</select>'+
        //  '<button class="btn btn-default" onclick="setRoleUser('+rid+')">' +"تایید"+'</button>'+
        //  '<br>'+
        //  '<br>'+
        //  '<br>'+ '<br>'+
        // '</div>'+
        //  '</div>'+
        // '</div>'+
        '</div> ';




    var obj = null;
    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act:'get_all_users_have_this_group',
            id:gid
        })
    ).done(function (data) {
            console.log("&&&&&&&&&&&&&"+data);

            obj = jQuery.parseJSON(data);


            /*
             create parameters for create table include:( tableplace , tableid, tablecontentid,arrays for header,array of footer,tablebody,havereport,reportbtn,haveoperation,operationbtn)
             */
            var tableplace="mainModalContent";
            var tableid= "secondtable";
            var tablebodiid="secondcontent";
            var havereport=false;
            var haveoperation=false;
            var header = ["ردیف", "نام", "نام خانوادگی", "شماره همراه", "ایمیل"];
            var footer = ["ردیف", "نام", "نام خانوادگی", "شماره همراه", "ایمیل"];
            //var btnr0= {type:"button", value:"پروفایل", class:"btn btn-xs btn-warning", function:"showProfile",modal:"data-toggle='modal' data-target='#mainModal'"};
            // var reportbtn={0:btnr0};
            var reportbtn=null;
            var operationbtn=null;
            var params=[gid];

            //var keys = Object.keys(obj['data'][0]);
            //console.log("ZZZZZZZZZZZZZZZZZZZZZZZ"+obj['data']);
            //console.log("************"+keys);
            if (obj['act'] == 'data') {

                //var acts = obj['data'];
                /*
                 create parameters for create table include:( tableplace , tableid, tablecontentid,arrays for header,array of footer,tablebody,havereport,reportbtn,haveoperation,operationbtn)
                 */



                tableCreator2(tableplace , tableid , tablebodiid , header , obj['data'] , footer , havereport , reportbtn , haveoperation , operationbtn,params);

                /*
                 execute data table javascript file
                 */

                //$('#maintable').DataTable( {
                //   dom: 'Bfrtip',
                //   buttons: [
                //         'copy', 'csv', 'excel', 'pdf', 'print'
                //   ]
                // } );

            }

            else if (obj['act'] == 'message') {

                tableCreator2(tableplace , tableid , tablebodiid , header , null , footer , havereport , reportbtn , haveoperation , operationbtn,params);

                prepare_message(obj['text'], 'fa' , obj['title'] );
                //showMessageModal('warning', obj['title'], obj['text']);
                return null;
            }
        }
    );



}

///////////////////////////////////////////////delete user group
function deleteUserGroup( params ){
    var id=params['0'];
    var gid=params['1'];
    console.log('asd');
    console.log ("0="+id +"1="+gid);

    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act: 'delete_user_group',
            id :id,
            groupid:gid

        }, function (data) {
            console.log("data====================="+data);

            var obj = jQuery.parseJSON(data);

            if (obj['act'] == 'data'){

                //alert(obj['data']);
                var table = $('#secondtable').DataTable();
                //table.clear();
                var el = document.getElementById('secondtable_wrapper');
                el.parentElement.removeChild(el);//innerHTML="secondtable_wrapper";

                //var sectab = document.getElementById('secondtable_wrapper').innerHTML="";






                $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
                        act:'get_user_group',
                        id:id
                    })
                ).done(function (data) {
                        //console.log("&&&&&&&&&&&&&"+data);

                        obj = jQuery.parseJSON(data);
                        //var keys = Object.keys(obj['data'][0]);
                        //console.log("ZZZZZZZZZZZZZZZZZZZZZZZ"+obj['data']);
                        //console.log("************"+keys);
                        if (obj['act'] == 'data') {

                            var acts = obj['data'];
                            /*
                             create parameters for create table include:( tableplace , tableid, tablecontentid,arrays for header,array of footer,tablebody,havereport,reportbtn,haveoperation,operationbtn)
                             */
                            var tableplace="mainModalContent";
                            var tableid= "secondtable";
                            var tablebodiid="secondcontent";
                            var havereport=false;
                            var haveoperation=true;
                            var header = ["ردیف", "نام گروه دسترسی","عملیات اجرایی"];
                            var footer = ["ردیف", "نام گروه دسترسی","عملیات اجرایی"];
                            var btno0= {type:"button", value:"حذف", class:"btn btn-xs btn-danger",triger:"deleteUserGroup" , function:"prepareConfirmModal",modal:"data-toggle='modal' data-target='#confirmModal'"};
                            var reportbtn=null;
                            var operationbtn={0:btno0};
                            var params=[id];



                            tableCreator2(tableplace , tableid , tablebodiid , header , obj['data'] , footer , havereport , reportbtn , haveoperation , operationbtn,params);

                            /*
                             execute data table javascript file
                             */

                            //$('#maintable').DataTable( {
                            //   dom: 'Bfrtip',
                            //   buttons: [
                            //         'copy', 'csv', 'excel', 'pdf', 'print'
                            //   ]
                            // } );

                        }

                        else if (obj['act'] == 'message') {
                            prepare_message(obj['text'], 'fa' , obj['title'] );
                            //showMessageModal('warning', obj['title'], obj['text']);
                            return null;
                        }
                    }
                );



                //table.row( $(this).parents('tr') ).remove().draw();
                //table.row('.selected').remove().draw( true );
                //loadMainPage();
                // var table = document.getElementById('maintable');

            }
            else if (obj['act'] == 'message') {
                //alert('message');
                console.log("not OK");
                //prepare_message( obj['text'] , 'fa' , obj['title'] );
            }
        })
    );
}




////////////////////////////////////////////////
///////////////////////////////////////////////delete role action
function deleteRoleAction( params ){
    var id=params['0'];
    var aid=params['1'];


    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act: 'delete_role_action',
            id :id,
            actionid:aid

        }, function (data) {
            console.log("data====================="+data);

            var obj = jQuery.parseJSON(data);

            if (obj['act'] == 'data'){

                //alert(obj['data']);
                var table = $('#secondtable').DataTable();
                //table.clear();
                var el = document.getElementById('secondtable_wrapper');
                el.parentElement.removeChild(el);//innerHTML="secondtable_wrapper";

                //var sectab = document.getElementById('secondtable_wrapper').innerHTML="";






                $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
                        act:'get_role_action',
                        id:id
                    })
                ).done(function (data) {
                        //console.log("&&&&&&&&&&&&&"+data);

                        obj = jQuery.parseJSON(data);
                        //var keys = Object.keys(obj['data'][0]);
                        //console.log("ZZZZZZZZZZZZZZZZZZZZZZZ"+obj['data']);
                        //console.log("************"+keys);


                        /*
                         create parameters for create table include:( tableplace , tableid, tablecontentid,arrays for header,array of footer,tablebody,havereport,reportbtn,haveoperation,operationbtn)
                         */
                        var tableplace="mainModalContent";
                        var tableid= "secondtable";
                        var tablebodiid="secondcontent";
                        var havereport=false;
                        var haveoperation=true;
                        var header = ["ردیف" ,"نام اکشن","توضیحات","عملیات اجرایی"];
                        var footer = ["ردیف" ,"نام اکشن","توضیحات","عملیات اجرایی"];
                        var btno0= {type:"button", value:"حذف", class:"btn btn-xs btn-danger",triger:"deleteRoleAction" , function:"prepareConfirmModal",modal:"data-toggle='modal' data-target='#confirmModal'"};
                        var reportbtn=null;
                        var operationbtn={0:btno0};
                        var params=[id];
                        if (obj['act'] == 'data') {

                            var acts = obj['data'];




                            tableCreator2(tableplace , tableid , tablebodiid , header , obj['data'] , footer , havereport , reportbtn , haveoperation , operationbtn,params);

                            /*
                             execute data table javascript file
                             */

                            //$('#maintable').DataTable( {
                            //   dom: 'Bfrtip',
                            //   buttons: [
                            //         'copy', 'csv', 'excel', 'pdf', 'print'
                            //   ]
                            // } );

                        }

                        else if (obj['act'] == 'message') {
                            tableCreator2(tableplace , tableid , tablebodiid , header , null , footer , havereport , reportbtn , haveoperation , operationbtn,params);
                            prepare_message(obj['text'], 'fa' , obj['title'] );
                            //showMessageModal('warning', obj['title'], obj['text']);
                            return null;
                        }
                    }
                );



                //table.row( $(this).parents('tr') ).remove().draw();
                //table.row('.selected').remove().draw( true );
                //loadMainPage();
                // var table = document.getElementById('maintable');

            }
            else if (obj['act'] == 'message') {
                //alert('message');
                console.log("not OK");
                //prepare_message( obj['text'] , 'fa' , obj['title'] );
            }
        })
    );
}


////////////////////////////////////////////delete user role
function deleteUserRole(params){



    var id=params['0'];
    var rid=params['1'];
    //console.log('asd');
    //console.log ("0="+id +"1="+gid);

    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act: 'delete_user_role',
            id :id,
            roleid:rid

        }, function (data) {
            console.log("data====================="+data);

            var obj = jQuery.parseJSON(data);

            if (obj['act'] == 'data'){

                //alert(obj['data']);
                var table = $('#secondtable').DataTable();
                //table.clear();
                var el = document.getElementById('secondtable_wrapper');
                el.parentElement.removeChild(el);//innerHTML="secondtable_wrapper";

                //var sectab = document.getElementById('secondtable_wrapper').innerHTML="";




















                $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
                        act:'get_user_role',
                        id:id
                    })
                ).done(function (data) {
                        //console.log("&&&&&&&&&&&&&"+data);

                        obj = jQuery.parseJSON(data);
                        //var keys = Object.keys(obj['data'][0]);
                        //console.log("ZZZZZZZZZZZZZZZZZZZZZZZ"+obj['data']);
                        //console.log("************"+keys);
                        if (obj['act'] == 'data') {

                            var acts = obj['data'];
                            /*
                             create parameters for create table include:( tableplace , tableid, tablecontentid,arrays for header,array of footer,tablebody,havereport,reportbtn,haveoperation,operationbtn)
                             */
                            var tableplace="mainModalContent";
                            var tableid= "secondtable";
                            var tablebodiid="secondcontent";
                            var havereport=false;
                            var haveoperation=true;
                            var header = ["ردیف", "نام گروه دسترسی","عملیات اجرایی"];
                            var footer = ["ردیف", "نام گروه دسترسی","عملیات اجرایی"];
                            var btno0= {type:"button", value:"حذف", class:"btn btn-xs btn-danger",triger:"deleteUserRole" , function:"prepareConfirmModal",modal:"data-toggle='modal' data-target='#confirmModal'"};
                            var reportbtn=null;
                            var operationbtn={0:btno0};
                            var params=[id];



                            tableCreator2(tableplace , tableid , tablebodiid , header , obj['data'] , footer , havereport , reportbtn , haveoperation , operationbtn,params);

                            /*
                             execute data table javascript file
                             */

                            //$('#maintable').DataTable( {
                            //   dom: 'Bfrtip',
                            //   buttons: [
                            //         'copy', 'csv', 'excel', 'pdf', 'print'
                            //   ]
                            // } );

                        }

                        else if (obj['act'] == 'message') {
                            prepare_message(obj['text'], 'fa' , obj['title'] );
                            //showMessageModal('warning', obj['title'], obj['text']);
                            return null;
                        }
                    }
                );
















                //table.row( $(this).parents('tr') ).remove().draw();
                //table.row('.selected').remove().draw( true );
                //loadMainPage();
                // var table = document.getElementById('maintable');

            }
            else if (obj['act'] == 'message') {
                //alert('message');
                console.log("not OK");
                //prepare_message( obj['text'] , 'fa' , obj['title'] );
            }
        })
    );




    //TODO: delete user role function
}

///////////////////////////////////// set group for user
function setUserGroup(uid){

    var groups= document.getElementById("selectgroup");
    var selectedidx =  groups.selectedIndex;
    var gname = groups.options[selectedidx].text;
    var gid = groups.options[selectedidx].value;


    var obj = null;
    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act:'set_user_group',
            id:uid,
            groupId:gid
        })
    ).done(function (data) {
            console.log("&&&&&&&&&&&&&"+data);

            obj = jQuery.parseJSON(data);
            //var keys = Object.keys(obj['data'][0]);
            //console.log("ZZZZZZZZZZZZZZZZZZZZZZZ"+obj['data']);
            //console.log("************"+keys);
            var table=document.getElementById('secondtable');

            if (obj['act'] == 'data') {


                var table = $('#secondtable').DataTable();
                if(table.rows()!=null){
                    var m = table.rows().indexes();
                    var maxim = Math.max.apply( Math , m )+2;
                    if(!isFinite(maxim)){
                        maxim=1;
                    }
                }
                else{
                    var maxim = 1;
                }
                var parameters=[uid , gid];

                table.row.add($( '<tr>'+'<td>'+(maxim)+'</td>'+
                    '<td>'+gname+'</td>'+
                    '<td><div class="btn-group btn-group-xs row"><input type="button" value="حذف" class="btn btn-xs btn-danger" onclick="prepareConfirmModal(['+parameters + '],'+"deleteUserGroup"+')" data-toggle="modal" data-target="#confirmModal" />'+
                    '</div></td>'
                )).draw();
                table.columns.adjust().draw();


                //loadGroup(uid);

            }

            else if (obj['act'] == 'message') {
                console.log("%%%%%%%%%%%%%%%%"+obj['text']);
                // prepare_message(obj['text'], 'fa' , obj['title'] );
                //showMessageModal('warning', obj['title'], obj['text']);
                return null;
            }
        }
    );
}

////////////////////////////////////set role for user
function setUserRole(uid){

    var roles= document.getElementById("selectrole");
    var selectedidx =  roles.selectedIndex;
    var rname = roles.options[selectedidx].text;
    var rid = roles.options[selectedidx].value;


    var obj = null;
    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act:'set_user_role',
            id:uid,
            roleId:rid
        })
    ).done(function (data) {
            console.log("&&&&&&&&&&&&&"+data);

            obj = jQuery.parseJSON(data);
            //var keys = Object.keys(obj['data'][0]);
            //console.log("ZZZZZZZZZZZZZZZZZZZZZZZ"+obj['data']);
            //console.log("************"+keys);
            var table=document.getElementById('secondtable');

            if (obj['act'] == 'data') {


                var table = $('#secondtable').DataTable();
                if(table.rows().indexes()!=null){
                    console.log("indexes="+table.rows().indexes());
                    var m = table.rows().indexes();
                    var maxim = Math.max.apply( Math , m )+2;
                    if(!isFinite(maxim)){
                        maxim=1;
                    }
                }
                else{
                    var maxim = 1;
                }
                var parameters=[uid , rid];

                table.row.add($( '<tr>'+'<td>'+(maxim)+'</td>'+
                    '<td>'+rname+'</td>'+
                    '<td><div class="btn-group btn-group-xs row"><input type="button" value="حذف" class="btn btn-xs btn-danger" onclick="prepareConfirmModal(['+parameters + '],'+"deleteUserRole"+')" data-toggle="modal" data-target="#confirmModal" />'+
                    '</div></td>'
                )).draw();
                table.columns.adjust().draw();


                //loadGroup(uid);

            }

            else if (obj['act'] == 'message') {
                console.log("%%%%%%%%%%%%%%%%"+obj['text']);
                prepare_message(obj['text'], 'fa' , obj['title'] );
                //showMessageModal('warning', obj['title'], obj['text']);
                return null;
            }
        }
    );
}









///////////////////////////////prepareConfirmModal
function prepareConfirmModal(params , func){

    $.when($.get("/View/lang/lang.xml" , function (xml) {
        var xmlDoc = xml;

        var x = xmlDoc.getElementsByTagName('confirm_message'); // get all msg tags as array, and x = first msg tag

        //console.log(x + func);
        var persianMsg = x[0].firstElementChild.innerHTML; // get persian data of a message
        var englishMsg = x[0].firstElementChild.nextElementSibling.innerHTML; // set y = x's child node
        var type = x[0].lastElementChild.innerHTML;



        document.getElementById('confirmModalContent').innerHTML = persianMsg;

        x = xmlDoc.getElementsByTagName(func); // get all msg tags as array, and x = first msg tag

        //console.log(x + func);
        persianMsg = x[0].firstElementChild.innerHTML; // get persian data of a message
        englishMsg = x[0].firstElementChild.nextElementSibling.innerHTML; // set y = x's child node
        type = x[0].lastElementChild.innerHTML;

        document.getElementById('confirmModalHeaderLabel').innerHTML = persianMsg;

        $('#confirmbtn').on('click' , function () {
            //console.log('asdd');
            eval(func)(params);
        });

    }));

}
//////////////////////////////delete user function

function deleteUser(params) {

    var deleteid=params['0'];

    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act: 'delete_user',
            id :deleteid

        }, function (data) {
            console.log("data====================="+data);

            var obj = jQuery.parseJSON(data);

            if (obj['act'] == 'data'){

                var e2 = document.getElementById('maintable_wrapper');
                console.log(e2.value);

                e2.parentElement.removeChild(e2);

                var obj = null;
                $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
                        act:'get_users'
                    })
                ).done(function (data) {

                        obj = jQuery.parseJSON(data);
                        var keys = Object.keys(obj['data'][0]);
                        //console.log("&&&&&&&&&&&&&"+obj['data'][0]);
                        //console.log("************"+keys);
                        if (obj['act'] == 'data') {

                            var acts = obj['data'];
                            /*
                             create parameters for create table include:( tableplace , tableid, tablecontentid,arrays for header,array of footer,tablebody,havereport,reportbtn,haveoperation,operationbtn)
                             */
                            var tableplace="mytable";
                            var tableid= "maintable";
                            var tablebodiid="gridview";
                            var havereport=true;
                            var haveoperation=true;
                            var header = ["ردیف", "نام", "نام خانوادگی", "شماره همراه", "ایمیل", "گزارشات", "عملیات اجرایی"];
                            var footer = ["ردیف", "نام", "نام خانوادگی", "شماره همراه", "ایمیل", "گزارشات", "عملیات اجرایی"];
                            var btnr0= {type:"button", value:"کسب و کار", class:"btn btn-xs btn-success", function:"default",modal:"data-toggle='modal' data-target='#mainModal'"};
                            var btnr1= {type:"button", value:"پروفایل", class:"btn btn-xs btn-warning", function:"showProfile",modal:"data-toggle='modal' data-target='#mainModal'"};
                            var btnr2= {type:"button", value:"باشگاه", class:"btn btn-xs btn-primary", function:"default",modal:"data-toggle='modal' data-target='#mainModal'"};
                            var btno0= {type:"button", value:"حذف", class:"btn btn-xs btn-danger",triger:"deleteUser" ,function:"prepareConfirmModal",modal:"data-toggle='modal' data-target='#confirmModal'"};
                            var btno1= {type:"button", value:"دسترسی", class:"btn btn-xs btn-warning", function:"loadRole",modal:"data-toggle='modal' data-target='#mainModal'"};
                            var btno2= {type:"button", value:"گروه دسترسی", class:"btn btn-xs btn-info",function:"loadGroup",modal:"data-toggle='modal' data-target='#mainModal'"};
                            var btno3= {type:"button", value:"فعال سازی", class:"btn btn-xs btn-default", function:"userActivation",modal:"data-toggle='modal' data-target='#messageModal'"};
                            var btno4= {type:"button", value:"ورود", class:"btn btn-xs btn-primary", function:"default",modal:"data-toggle='modal' data-target='#mainModal'"};
                            var reportbtn={0:btnr0 , 1:btnr1 ,2:btnr2};
                            var operationbtn={0:btno0 , 1:btno1 ,2:btno2,3:btno3,4:btno4};
                            var params=null;


                            tableCreator2(tableplace , tableid , tablebodiid , header , obj['data'] , footer , havereport , reportbtn , haveoperation , operationbtn,params);

                        }

                        else if (obj['act'] == 'message') {
                            prepare_message(obj['text'], 'fa' , obj['title'] );
                            //showMessageModal('warning', obj['title'], obj['text']);
                            return null;
                        }
                    }
                );

            }
            else if (obj['act'] == 'message') {
                //alert('message');
                //console.log("OK");
                //prepare_message( obj['text'] , 'fa' , obj['title'] );
            }
        })
    );


}
///////////////////////////////////////////////////////
function userActivation(uid){
    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act: 'admin_user_active',
            id :uid

        }, function (data) {

            var obj = jQuery.parseJSON(data);

            if (obj['act'] == 'data'){

                //nothing to do
            }
            else if (obj['act'] == 'message') {
                //prepare_message( obj['text'] , 'fa' , obj['title'] );
                document.getElementById('messageModalContent').innerHTML=obj['text'];
                document.getElementById('messageModalHeaderLabel').innerHTML='User Activation';

            }
        })
    );

}
/////////////////////////////////////////////////////////////////////////////

function setvars2( x,y ) {

    //console.log(x);
    document.getElementById(y).innerHTML = x ;
}
/************ show modal *********/
// create modal and show message to user
function showMessageModal(type , title , message ){
    //alert(type);
    var btnClass = 'btn btn-primary';
    switch (type)
    {
        case 'warning':
            btnClass = 'btn btn-warning';
            break;
        case 'danger':
            btnClass = 'btn btn-danger';
            break;
        case 'info':
            btnClass = 'btn btn-info';
            break;
        case 'success':
            btnClass = 'btn btn-success';
            break;

        default:
            //btnClass = 'btn btn-warning';
            break;
    }
   //alert(type+ ' '+btnClass);

    $('#messageModal  h4').html(title);
    $('#messageModal .modal-body #messageModalContent').html(message);
    $('#messageModal  button').attr('class', btnClass );
    $('#messageModal .modal-footer button').html('بستن');
    $('#messageModal').modal('show');
}

/*
admin user register
 */
function adminUserRegister(){

    if( document.getElementById('checkfirstname').value=="false" )
    {
        // prepare message to show fill essential data
        document.getElementById('messageModalContent').innerHTML = "please_fill_firstname";
        //console.log("please fill all essential fields");
        $('#messageModal').modal('show');
        return;

    }
    if( document.getElementById('checklastname').value=="false" )
    {
        // prepare message to show fill essential data
        document.getElementById('messageModalContent').innerHTML = "please_fill_lastname";
        //console.log("please fill all essential fields");
        $('#messageModal').modal('show');
        return;
    }
    if( document.getElementById('checkpassword').value=="false" )
    {
        // prepare message to show fill essential data
        document.getElementById('messageModalContent').innerHTML = "please_fill_password";
        //console.log("please fill all essential fields");
        $('#messageModal').modal('show');
        return;
    }
    if( document.getElementById('checkrepassword').value=="false" )
    {
        // prepare message to show fill essential data
        document.getElementById('messageModalContent').innerHTML = "please_fill_password_confirm";
        //console.log("please fill all essential fields");
        $('#messageModal').modal('show');
        return;
    }
    if( document.getElementById('checkmobile').value=="false" )
    {
        // prepare message to show fill essential data
        document.getElementById('messageModalContent').innerHTML = "please_fill_mobile_number";
        //console.log("please fill all essential fields");
        $('#messageModal').modal('show');
        return;
    }
    if( document.getElementById('checkemail').value=="false" )
    {
        // prepare message to show fill essential data
        document.getElementById('messageModalContent').innerHTML = "please_fill_email";
        //console.log("please fill all essential fields");
        $('#messageModal').modal('show');
        return;
    }
    if( document.getElementById('checkaddress').value=="false" )
    {
        // prepare message to show fill essential data
        document.getElementById('messageModalContent').innerHTML = "please_fill_address";
        //console.log("please fill all essential fields");
        $('#messageModal').modal('show');
        return;

    }





    // find all input tag that id is like "mobileX"
    //
    var matches = [];
    var elems = document.getElementsByTagName("input");
    for (var i=0; i<elems.length; i++) {
        if (elems[i].id.indexOf("mobile") == 0)
            matches.push(elems[i]);
    }
    var otherphonelength = matches.length;

    var otherphone=[];
    for(var i = 0 , j = 0 ; i < matches.length ; i++ )
    {
        if( matches[i].id == "mobile")
        {

        }
        else {
            otherphone[j++] = matches[i].value ;
        }
    }

    //console.log(otherphone);


    // if all data filled, then call ajax to register user
    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {

            act: 'admin_user_register',
            firstname : document.getElementById('firstname').value ,
            lastname : document.getElementById('lastname').value ,
            gender : document.getElementById('sex').value,
            email : document.getElementById('email').value,
            state : document.getElementById('state').value,
            city : document.getElementById('city').value,
            region : document.getElementById('region').value,
            address:document.getElementById('address').value,
            password : CryptoJS.MD5( document.getElementById('password').value ).toString(),
            mobilenumber:document.getElementById('mobile').value,
            otherphone:otherphone


        }, function (data) {
        console.log("data=====================: "+data);
        var obj = jQuery.parseJSON(data);
            if (obj['act'] == 'data'){

                //alert('data');
            }
            else if (obj['act'] == 'message') {


                resetform('registerForm');

                /*
                show confirm modal

                 */
                prepare_message( obj['text'] , 'fa' , obj['title'] );

                /*
                clear form
                 */

                //document.getElementById('registerForm').reset();



                /*
                show loading icon
                 */

                //is automatic

                /*
                hide modal
                 */

                $('#mainModal').modal('hide');

                var el = document.getElementById('maintable_wrapper');
                el.parentElement.removeChild(el);

                var obj = null;
                $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
                        act:'get_users'
                    })
                ).done(function (data) {

                        obj = jQuery.parseJSON(data);
                        var keys = Object.keys(obj['data'][0]);
                        //console.log("&&&&&&&&&&&&&"+obj['data'][0]);
                        //console.log("************"+keys);
                        if (obj['act'] == 'data') {

                            var acts = obj['data'];
                            /*
                             create parameters for create table include:( tableplace , tableid, tablecontentid,arrays for header,array of footer,tablebody,havereport,reportbtn,haveoperation,operationbtn)
                             */
                            var tableplace="mytable";
                            var tableid= "maintable";
                            var tablebodiid="gridview";
                            var havereport=true;
                            var haveoperation=true;
                            var header = ["ردیف", "نام", "نام خانوادگی", "شماره همراه", "ایمیل", "گزارشات", "عملیات اجرایی"];
                            var footer = ["ردیف", "نام", "نام خانوادگی", "شماره همراه", "ایمیل", "گزارشات", "عملیات اجرایی"];
                            var btnr0= {type:"button", value:"کسب و کار", class:"btn btn-xs btn-success", function:"default",modal:"data-toggle='modal' data-target='#mainModal'"};
                            var btnr1= {type:"button", value:"پروفایل", class:"btn btn-xs btn-warning", function:"showProfile",modal:"data-toggle='modal' data-target='#mainModal'"};
                            var btnr2= {type:"button", value:"باشگاه", class:"btn btn-xs btn-primary", function:"default",modal:"data-toggle='modal' data-target='#mainModal'"};
                            var btno0= {type:"button", value:"حذف", class:"btn btn-xs btn-danger",triger:"deleteUser" ,function:"prepareConfirmModal",modal:"data-toggle='modal' data-target='#confirmModal'"};
                            var btno1= {type:"button", value:"دسترسی", class:"btn btn-xs btn-warning", function:"loadRole",modal:"data-toggle='modal' data-target='#mainModal'"};
                            var btno2= {type:"button", value:"گروه دسترسی", class:"btn btn-xs btn-info",function:"loadGroup",modal:"data-toggle='modal' data-target='#mainModal'"};
                            var btno3= {type:"button", value:"فعال سازی", class:"btn btn-xs btn-default", function:"userActivation",modal:"data-toggle='modal' data-target='#messageModal'"};
                            var btno4= {type:"button", value:"ورود", class:"btn btn-xs btn-primary", function:"default",modal:"data-toggle='modal' data-target='#mainModal'"};
                            var reportbtn={0:btnr0 , 1:btnr1 ,2:btnr2};
                            var operationbtn={0:btno0 , 1:btno1 ,2:btno2,3:btno3,4:btno4};
                            var params=null;


                            tableCreator2(tableplace , tableid , tablebodiid , header , obj['data'] , footer , havereport , reportbtn , haveoperation , operationbtn,params);

                        }

                        else if (obj['act'] == 'message') {
                            prepare_message(obj['text'], 'fa' , obj['title'] );
                            //showMessageModal('warning', obj['title'], obj['text']);
                            return null;
                        }
                    }
                );


            }
        })
    );

}


/*****************
 * reset label fileds in modal
 */
function rst(){
    document.getElementById("namemsg").innerHTML ="";
    document.getElementById("lastnamemsg").innerHTML ="";
    document.getElementById("passwordmsg").innerHTML ="";
    document.getElementById("emailmsg").innerHTML ="";
    document.getElementById("addressmsg").innerHTML ="";
    document.getElementById("passconfirmmsg").innerHTML ="";
    document.getElementById("mobilemsg").innerHTML ="";
}

function resetform( formid )
{
    var tag = document.getElementById( formid );
    var childs = tag.elements;
    var labels = tag.getElementsByTagName('label');

    //console.log( 'in reset form');
    for( var i = 0 ; i < childs.length ; i++ )
    {
        var tagname = childs[i].tagName;

        //console.log( 'in reset form ' + tagname);
        switch (tagname)
        {
            case 'INPUT':
                var tagtype = childs[i].getAttribute('type');

                //console.log( 'in reset form tag type ' + tagtype);
                switch( tagtype )
                {
                    case 'text':
                    case 'email':
                    case 'number':
                    case 'password':
                        childs[i].value = '';
                        break;

                    case 'radio':
                    case 'checkbox':
                        childs[i].checked = false;
                        break;
                }
                break;

            case 'SELECT':
                childs[i].selectedIndex = 0 ;

                break;

            case 'TEXTAREA':
                childs[i].value ='';
                break;
        }

    }
    for( var i =  0 ; i < labels.length ; i++ )
    {
        label = labels[i];
        console.log( 'in reset form tag type ' + label );

        if( label.getAttribute('id') && label.getAttribute('id').match('msg'))
        {
            label.innerHTML = '';
        }

    }
}

/******************************
 * show admin register popup
 */

function showAdminRegister(){
    loadStates();
    window.phonenum=0;
    window.phoneid=0;

    document.getElementById("mainModalHeaderLabel").innerHTML='<h2>ایجاد کاربر</h2>'+ '<br>'+ '<br>';
    document.getElementById("mainModalContent").innerHTML = '<section class="content">'+
        <!-- contact phones -->

    <!-- form start -->
    '<form id="registerForm" class="form-horizontal">'+

        '<div class="row" >'+

        '<div class="col-md-12 col-sm-12">'+
        <!-- Horizontal Form -->
   '<div class="box box-info">'+
        '<div class="box-header with-border">'+
        '<h3 class="box-title">تلفن تماس</h3>'+
    '</div>'+
    <!-- /.box-header -->
    '<div class="box-body" id="phonebox">'+
        '<div class="form-group">'+
        '<label for="mobile" class="col-sm-2 control-label">تلفن همراه  <span style="color:#FF0000;">*</span></label>'+
            '<div class="col-sm-4">'+
            '<input type="text" class="form-control" tabindex="1" id="mobile"  placeholder="09123456789">'+
            '</div>'+
        '<div class="col-sm-1">'+
            '<input type="button" tabindex="4" class="btn btn-info" value="+" onclick="addElement()">'+
        '</div>'+
        '<div class="col-sm-5">'+
            '<input type="hidden" id="checkmobile" value="false" style="display:none;">'+
            '<label id="mobilemsg" class="col-sm-12"></label>'+
        '</div>'+


        '</div>'+


        '</div><!-- /.box-body -->'+

    '</div><!-- /.box -->'+
    '<!-- general form elements disabled -->'+
    '<!-- /.box -->'+
    '</div>'+
    '</div>'+


    '<!-- personal info -->'+
    '<div class="row" >'+
        '<div class="col-md-12 ">'+
        '<!-- Horizontal Form -->'+
    '<div class="box box-info">'+
        '<div class="box-header with-border">'+
        '<h3 class="box-title">مشخصات فردی</h3>'+
    '</div>'+
    '<!-- /.box-header -->'+
    '<!-- form start -->'+
    '<div class="box-body">'+
     '   <div class="form-group">'+
      '  <label for="firstname" class="col-sm-2 control-label">نام <span style="color:#FF0000;">*</span> </label>'+
        '<div class="col-sm-3">'+
        '<input type="text" class="form-control" name="firstname" id="firstname"  tabindex="7" placeholder="نام">'+
        '</div>'+
        '<div class="col-sm-7">'+
        '<label id="namemsg" class="col-sm-12"></label>'+
        '<input type="hidden"  id="checkfirstname" value="false" style="display:none;">'+
        '</div>'+
        '</div>'+

        '<div class="form-group">'+
        '<label for="lastname" class="col-sm-2 control-label"> نام خانوادگی <span style="color:#FF0000;">*</span></label>'+
    '<div class="col-sm-3">'+
        '<input type="text" class="form-control" name="lastname" id="lastname"  tabindex="8" placeholder="نام خانوادگی">'+
        '</div>'+
        '<div class="col-sm-7">'+
        '<label id="lastnamemsg" class="col-sm-12"></label>'+
        '<input type="hidden" id="checklastname" value="false" style="display:none;">'+
        '</div>'+
        '</div>'+

        '<div class="form-group">'+
        '<label for="lastname" class="col-sm-2 control-label">جنسیت</label>'+
        '<div class="col-sm-3">'+
        '<select class="selectpicker" id="sex" data-live-search="false" tabindex="9">'+
        '<option data-tokens="ketchup mustard" value="1">مرد</option>'+
        '<option data-tokens="mustard" value="0">زن</option>'+
        '</select>'+
        '</div>'+
        '<div class="col-sm-1">'+
        '<label id="sexsmsg"></label>'+
        '</div>'+
        '</div>'+

        '<div class="form-group">'+
        '<label for="email" class="col-sm-2 control-label"  >ایمیل</label>'+
        '<div class="col-sm-3">'+
        '<input type="email" class="form-control" id="email" tabindex="10" placeholder="Email">'+
    '</div>'+
    '<div class="col-sm-7">'+
     '   <label id="emailmsg" class="col-sm-12"></label>'+
        '<input type="hidden" id="checkemail" value="true"'+
      '  </div>'+
       ' </div>'+

        '</div><!-- /.box-body -->'+

    '</div><!-- /.box -->'+
    '</div>'+

    '</div>'+


    '<!-- address info -->'+
    '<div class="row" >'+
        '<div class="col-md-12 ">'+
        '<!-- Horizontal Form -->'+
    '<div class="box box-info">'+
        '<div class="box-header with-border">'+
        '<h3 class="box-title">محل سکونت</h3>'+
    '</div>'+
    '<!-- /.box-header -->'+
    '<!-- form start -->'+
    '<div class="box-body">'+
        '<div class="form-group">'+
        '<label for="lastname" class="col-sm-2 control-label" >استان</label>'+
        '<div class="col-sm-3">'+
        '<select class="selectpicker" id="state" data-live-search="true" tabindex="11">'+
        '<option data-tokens="ketchup mustard" value="0">استان</option>'+
        '</select>'+
        '</div>'+
        '<div class="col-sm-1">'+
        '<label id="statemsg"></label>'+
        '</div>'+
        '</div>'+

        '<div class="form-group">'+
        '<label for="lastname" class="col-sm-2 control-label">شهر</label>'+
        '<div class="col-sm-3">'+
        '<select class="selectpicker" id="city" data-live-search="true" tabindex="12">'+
        '<option data-tokens="ketchup mustard" value="0">شهر</option>'+
        '</select>'+
        '</div>'+
        '<div class="col-sm-1">'+
        '<label id="citymsg"></label>'+
        '</div>'+
        '</div>'+

        '<div class="form-group">'+
        '<label for="lastname" class="col-sm-2 control-label">منطقه</label>'+
        '<div class="col-sm-3">'+
        '<select class="selectpicker" id="region" data-live-search="true" tabindex="13">'+
        '<option data-tokens="ketchup mustard" value="0">منطقه</option>'+
        '</select>'+
        '</div>'+
        '<div class="col-sm-1">'+
        '<label id="regionmsg"></label>'+
        '</div>'+
        '</div>'+

        '<div class="form-group">'+
        '<label for="email" class="col-sm-2 control-label">آدرس</label>'+
        '<div class="col-sm-3">'+
        '<textarea type="address" class="form-control" name="address" tabindex="13" id="address"  placeholder="آدرس محل سکونت" cols="50" rows="5"> </textarea>'+
        '</div>'+
        '<div class="col-sm-7">'+
        '<label id="addressmsg" class="col-sm-12"></label>'+
        '<input type="hidden" id="checkaddress" value="true" style="display:none;">'+
        '</div>'+
        '</div>'+

        '</div><!-- /.box-body -->'+

    '</div><!-- /.box -->'+
    '</div>'+

    '</div>'+


    '<!-- passwords  -->'+
    '<div class="row" >'+
     '   <div class="col-md-12 ">'+
       '<!-- Horizontal Form -->'+
    '<div class="box box-info">'+
     '   <div class="box-header with-border">'+
      '  <h3 class="box-title"> رمز عبور</h3>'+
    '</div>'+
    '<!-- /.box-header -->'+
    '<!-- form start -->'+
    '<div class="box-body">'+

        '<div class="form-group">'+
        '<label for="password" class="col-sm-2 control-label">رمز عبور <span style="color:#FF0000;">*</span></label>'+
    '<div class="col-sm-3">'+
        '<input type="password" class="form-control" name="password"  tabindex="14" id="password" placeholder="رمز عبور">'+
        '</div>'+
        '<div class="col-sm-7">'+
        '<label id="passwordmsg" class="col-sm-12"></label>'+
        '<input type="hidden" id="checkpassword" value="false" style="display:none;">'+
        '</div>'+
        '</div>'+

        '<div class="form-group">'+
        '<label for="password-confirmation" class="col-sm-2 control-label">تکرار رمز عبور  <span style="color:#FF0000;">*</span> </label>'+
    '<div class="col-sm-3">'+
        '<input type="password" class="form-control" name="password-confirmation" tabindex="15" id="password-confirmation" placeholder="تکرار رمز عبور">'+
        '</div>'+
        '<div class="col-sm-7">'+
        '<label id="passconfirmmsg" class="col-sm-12"></label>'+
        '</div>'+
        '</div>'+
        '</div><!-- /.box-body -->'+
        '<input type="hidden" id="checkrepassword" value="false" style="display:none;">'+
        '<div class="box-footer">'+

        '</div><!-- /.box-footer -->'+
    '</div><!-- /.box -->'+
    '</div>'+

    '</div>'+

   /* '<!-- business  -->'+
    '<div class="row" >'+
        '<div class="col-md-12 ">'+
        <!-- Horizontal Form -->
    '<div class="box box-info">'+
        '<div class="box-header with-border">'+
        '<h3 class="box-title">کسب و کار</h3>'+
    '</div>'+
    '<!-- /.box-header -->'+
    '<!-- form start -->'+
    '<div class="box-body">'+

        '<div class="form-group">'+
        '<label for="supplier" class="col-sm-2 control-label"> </label>'+
        '<div class="col-sm-3">'+
        '<input type="checkbox"   name="supplier" id="supplier" tabindex="16" >'+
        '<label for="supplier" class="  control-label">آیا کاربر تامین کننده است؟</label>'+
    '</div>'+
    '<div class="col-sm-1">'+
        '<label id="passwordmsg"></label>'+
        '</div>'+
        '</div>'+

        '<div class="form-group">'+
        '<label for="business" class="col-sm-2 control-label">کسب و کارها</label>'+
    '<div class="col-sm-3">'+
        '<select class="selectpicker" id="business" data-live-search="true" tabindex="17">'+
        '<option data-tokens="ketchup mustard" value="0">کسب و کارها</option>'+
    '</select>'+
    '</div>'+
    '<div class="col-sm-1">'+
        '<label id="businessmsg"></label>'+
        '</div>'+
        '</div>'+
        '</div><!-- /.box-body -->'+
    '<div class="box-footer">'+
        '<button type="submit" class="btn btn-default" tabindex="18">کسب و کار اول</button>'+
    '<button type="submit" class="btn btn-info " tabindex="19">کسب و کار دوم</button>'+
    '</div><!-- /.box-footer -->'+
    '</div><!-- /.box -->'+
    '</div>'+

    '</div>'+

    '<div class="row" >'+
        '<div class="col-md-12 ">'+
        '<!-- Horizontal Form -->'+*/
        '<div class="text-center">'+
        '<input id="register" type="button" class="btn btn-lg btn-success" value="ثبت" tabindex="20" data-toggle="modal" data-target="#Modal2" onclick=\"adminUserRegister()\">'+
        '<input type="reset" class="btn btn-lg btn-warning" value="پاک کن" onclick=\"resetform(\'registerForm\')\" tabindex="21">'+
        '</div><!-- /.box-footer -->'+''
    '</div>'+

    '</div>'+


    '</form>'+



    '</section><!-- /.content -->';
    $('#password-confirmation').on('change' , function () {
        check_repeat_password( "password-confirmation" , "checkrepassword", "passconfirmmsg", this.value , $('#password').val() );
    });
    $('#password').on('change' , function () {
        general_validation( "password" , "checkpassword", "passwordmsg", this.value , "password" );
    });
    $('#address').on('change' , function () {
        general_validation( "address" , "checkaddress", "addressmsg", this.value , "address" );
    });
    $('#email').on('change' , function () {
        general_validation("email", "checkemail", "emailmsg", this.value , "email" );
    });
    $('#lastname').on('keyup' , function () {
        general_validation( "lastname" , "checklastname", "lastnamemsg", this.value , "name" );
    });
    $('#firstname').on('keyup' , function () {

        general_validation("firstname", "checkfirstname", "namemsg", this.value , "name" );
    });
    $('#mobile').on('change' , function () {
        general_validation( "mobile" , "checkmobile", "mobilemsg", this.value , "mobile" );
    });
    $('#state').on('change' , function () {
        loadCities(this.value);
    });
    $('#city').on('change' , function () {
        loadRegions(this.value);
    });

}

/************
 * add element function , for add new phone number
 */
function addElement(){

    if( window.phonenum < 10 )
    {
        window.phonenum++;
        window.phoneid++;
        var phone = document.createElement('phones');
        phone.innerHTML='<div class="form-group" id="mobilediv'+window.phoneid+'"'+'>'+
            '<label for="mobile'+window.phoneid+'"'+'class="col-sm-2 control-label"> </label>'+
            '<div class="col-sm-4">'+
            '<input type="text" class="form-control" id="mobile'+window.phoneid+'"'+'tabindex="5" placeholder="">'+
            '</div>'+
            '<div class="col-sm-1">'+
            '<input type="button" class="btn btn-warning" value="-" tabindex="6" onclick="deleteElement('+window.phoneid+')" >'+
            '</div>'+
            '<div class="col-sm-5">'+
            '<input type="hidden" id="checkmobile'+window.phoneid+'"'+' value="true" style="display:none;">'+
            '<label id="mobilemsg'+window.phoneid+'"'+' class="col-sm-12"></label>'+
            '</div>'+
            '</div>';
        document.getElementById('phonebox').appendChild(phone);

        var pid=window.phoneid;
        console.log('#mobile'+pid);

        $('#mobile'+pid).on('keyup' , function () {
            var arg1="mobile"+pid;
            var arg2="checkmobile"+pid;
            var arg3 = "mobilemsg"+pid;
            var arg4=document.getElementById(arg1).value;
            //alert("arg1="+arg1+"arg2="+arg2+"arg3="+arg3+"arg4="+arg4);
            general_validation( arg1 ,arg2 , arg3, arg4 , "phone" );
        });
    }
    else
    {
        prepare_message('max phone number exceeded' , 'fa' , 'err');
    }


/*
    for(var pid=1;pid<=window.phoneid;pid++){
        console.log("^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^pid="+pid);
        var pcheck=document.getElementById('#mobile'+pid);
        if(typeof pcheck == 'undefined'){
            console.log("undefined");
            //nothing to do
        }
        else{
            console.log("mobile"+pid);
            $('#mobile'+pid).on('change' , function () {
                general_validation( "mobile"+pid , "checkmobile"+pid, "mobilemsg"+pid, this.value , "phone" );
            });
        }

    }*/

}
/**************************
 * delete element function , for remove phone number
 */
function deleteElement( id ){
    document.getElementById("mobilediv"+id).parentElement.remove();
    window.phonenum--;
}

/***************************
 * show profile information
 */
function showProfile(idd){
    console.log("showprofile: "+idd);
    if(idd==0){
        idd = window.myid;
    }

    // this line to load state drop down list, for edit profile
    loadStates();

    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act: 'get_user_profile',
            id: idd
        }, function (data) {
         //console.log("data====================="+data);
        //console.log("**************************");
            var obj = jQuery.parseJSON(data);
            //console.log(obj['data']);




        if (obj['act'] == 'data'){

            // load parameters from data
            var firstname = ( obj['data']['FirstName'] == undefined ? '' : obj['data']['FirstName'] );
            //console.log( obj['data'] );
            var lastname = ( obj['data']['LastName'] == undefined ? '' : obj['data']['LastName'] ) ;
            var gender = ( obj['data']['Gender'] == undefined ? '' :obj['data']['Gender'] );
            var mobilenumber = ( obj['data']['Telephone'] == undefined ? '' :obj['data']['Telephone'] ) ;
            var creationdate = ( obj['data']['CreationDateJalali'] == undefined ? '' : obj['data']['CreationDateJalali'] );
            var address = ( obj['data']['Address'] == undefined ? '' : obj['data']['Address'] );
            var statename = ( obj['data']['StateName'] == undefined ? '' : obj['data']['StateName'] );
            var cityname = ( obj['data']['CityName'] == undefined ? '' : obj['data']['CityName'] );
            var regionname = ( obj['data']['RegionName'] == undefined ? '' : obj['data']['RegionName']);
            var email = ( obj['data']['Email'] == undefined ? '' : obj['data']['Email'] );
            var description = (obj['data']['Description'] == undefined ? '' : obj['data']['Description']);
            var userid = ( obj['data']['UserId'] == undefined ? null : obj['data']['UserId'] );
            var status = ( obj['data']['IsActive'] == 0 ? 'deactive' : 'active');
            var username = ( obj['data']['UserName'] == undefined ? '' : obj['data']['UserName']);
            var editmobile = ( obj['data']['editmobile'] == undefined ? '' :obj['data']['editmobile'] );
            var state = ( obj['data']['StateId'] == undefined ? 0 : obj['data']['StateId']);
            var city = ( obj['data']['CityId'] == undefined ? 0 : obj['data']['CityId'] );
            var region = ( obj['data']['RegionId'] == undefined ? 0 : obj['data']['RegionId']);
            console.log( state + city + region );


            // set data into edit profile show div
            document.getElementById('fname-show').innerHTML=firstname;
            document.getElementById('lname-show').innerHTML=lastname;
            document.getElementById('mobile-show').innerHTML=mobilenumber;
            document.getElementById('gender-show').innerHTML=( gender == 0 ?  'زن' : 'مرد' ) ;
            document.getElementById('registertime').innerHTML=creationdate;
            document.getElementById('address-show').innerHTML=address;
            document.getElementById('state-show').innerHTML=statename;
            document.getElementById('city-show').innerHTML=cityname;
            document.getElementById('region-show').innerHTML=regionname;
            document.getElementById('description-show').innerHTML=description;
            document.getElementById('status').innerHTML=status;


            //set data to edit form

            document.getElementById('firstname').value=firstname;
            document.getElementById('lastname').value=lastname;
            document.getElementById('email').value=email;
            document.getElementById('address').value=address;
            document.getElementById('userid').value=userid;
            document.getElementById('mobile').value=username;

            // set default value of user address
            var element = document.getElementById('state');

            console.log( 'stateelement :'+ element );
            console.log( 'state :'+ state );

            var option = $('#state option[value="'+state+'"]');
            element.selectedIndex = option.index();

            if(state>0){
                loadCities( state , city );
            }
           /* if( state > 0 )
            {

                var cityelement = document.getElementById('city');
                loadCities( state );
                //wait(2000);
                //element = document.getElementById('city');
                //element.value = city;
                //console.log( 'city :'+ city );
                option = $('#city option[value="'+city+'"]');
                cityelement.selectedIndex = option.index();

                console.log( 'cityelement :'+ cityelement );
                console.log('city:'+city);
            }*/


            if( city > 0 )
            {
                loadRegions( city , region );


               // option = $('#region option[value="'+region+'"]');
                //document.getElementById('region').selectedIndex = option.index();
            }
            // check user can edit mobile or not
            if( editmobile == 0 )
            {
                // user does not have permission to edit this
                document.getElementById("mobile").disabled = true;
            }
        }


            else if (obj['act'] == 'message') {
                prepare_message( obj['text'] , 'fa' , obj['title'] );
            }
        })
    );





    //console.log("id========================="+idd);

    document.getElementById('mainModalHeaderLabel').innerHTML='<h2>ویرایش کاربر</h2>'+ '<br>'+ '<br>';
    document.getElementById('mainModalContent').innerHTML =
        '<div id="profile-field-show" class="content" style="display:block;">'+
        '<!— contact phones —>'+
        '<div class="row" >'+
        '<div class="col-md-12 ">'+
        '<!— Horizontal Form —>'+
        '<div class="box box-info">'+
        ' <div class="box-header with-border">'+
        '<h3 class="box-title">مشخصات فردی</h3>'+
        '</div>'+
        '<!— /.box-header —>'+
        '<!— form start —>'+
        '<div class="form-horizontal">'+
        '<div class="box-body">'+
        '<div class="form-group">'+
        '<label for="fname-show" class="col-sm-2 control-label">نام</label>'+
        '<div class="col-sm-3">'+
        '<p  class="form-control" id="fname-show" > </p>'+
        '</div>'+
        '</div>'+
        '<div class="form-group">'+
        '<label for="lname-show" class="col-sm-2 control-label">نام خانوادگی</label>'+
        '<div class="col-sm-3">'+
        '<p class="form-control" id="lname-show" ></p>'+
        '</div>'+
        '</div>'+
        '<div class="form-group">'+
        '<label for="gender-show" class="col-sm-2 control-label">جنسیت</label>'+
        '<div class="col-sm-3">'+
        '<p class="form-control" id="gender-show" > </p>'+
        '</div>'+
        '</div>'+
        '<div class="form-group">'+
        '<label for="mobile-show" class="col-sm-2 control-label">شماره همراه</label>'+
        '<div class="col-sm-3">'+
        '<p class="form-control" id="mobile-show" > </p>'+
        '</div>'+
        '</div>'+

        '</div><!— /.box-body —>'+

        '</div>'+
        '</div><!— /.box —>'+
        '<!— general form elements disabled —>'+
        '<!— /.box —>'+
        '</div>'+
        '</div>'+
        '<div class="row" >'+
        '<div class="col-md-12 ">'+
        '<!— Horizontal Form —>'+
        '<div class="box box-info">'+
        '<div class="box-header with-border">'+
        '<h3 class="box-title">محل سکونت</h3>'+
        '</div>'+
        '<!— /.box-header —>'+
        '<!— form start —>'+
        '<div class="form-horizontal">'+
        '<div class="box-body">'+
        '<div class="form-group">'+
        '<label for="state-show" class="col-sm-2 control-label">استان</label>'+
        '<div class="col-sm-3">'+
        '<p class="form-control" id="state-show" > </p>'+
        '</div>'+
        '</div>'+
        '<div class="form-group">'+
        '<label for="city-show" class="col-sm-2 control-label">شهر</label>'+
        '<div class="col-sm-3">'+
        '<p class="form-control" id="city-show" ></p>'+
        '</div>'+
        '</div>'+
        '<div class="form-group">'+
        '<label for="region-show" class="col-sm-2 control-label">منطقه</label>'+
        '<div class="col-sm-3">'+
        '<p class="form-control" id="region-show" > </p>'+
        '</div>'+
        '</div>'+
        '<div class="form-group">'+
        '<label for="address" class="col-sm-2 control-label">آدرس</label>'+
        '<div class="col-sm-3">'+
        '<p class="form-control" id="address-show" ></p>'+
        '</div>'+
        '</div>'+

        '</div><!— /.box-body —>'+

        '</div>'+

        '</div><!— /.box —>'+
        '<!— general form elements disabled —>'+
        '<!— /.box —>'+
        '</div>'+
        '</div>'+


        '<div class="row" >'+
        '<div class="col-md-12 ">'+
        '<!— Horizontal Form —>'+
        '<div class="box box-info">'+
        '<div class="box-header with-border">'+
        '<h3 class="box-title">مشخصات کاربری</h3>'+
        '</div>'+
        '<!— /.box-header —>'+
        '<!— form start —>'+
        '<div class="form-horizontal">'+
        '<div class="box-body">'+
        '<div class="form-group">'+
        '<label for="registertime" class="col-sm-2 control-label">زمان ثبت نام</label>'+
        '<div class="col-sm-3">'+
        '<p class="form-control" id="registertime"> </p>'+
        '</div>'+
        '</div>'+

        '<div class="form-group">'+
        '<label for="status" class="col-sm-2 control-label">وضعیت</label>'+
        '<div class="col-sm-3">'+
        '<p class="form-control" id="status" ></p>'+
        '</div>'+
        '</div>'+
        '<div class="form-group">'+
        '<label for="type" class="col-sm-2 control-label">توضیحات</label>'+
        '<div class="col-sm-3">'+
        '<p  class="form-control" id="description-show" ></p>'+
        '</div>'+
        '</div>'+
        '</div><!— /.box-body —>'+

        '</div>'+

        '</div><!— /.box —>'+
        '<!— general form elements disabled —>'+
        '<!— /.box —>'+
        '</div>'+
        '</div>'+

        '<div class="box-footer">'+
        '<button type="button" class="btn btn-info pull-right  col-md-2" onclick=\"switchtoedit()\">ویرایش</button>'+
        '</div>'+
        '<!— /.box-footer —>'+
        '</div>';
         //until here, we create a div to show data


        document.getElementById('mainModalContent').innerHTML +=

        // from here we create a form to edit data
        '<form id="profile-field-edit" class="content" style="display:none;">'+


        '<div class="row">'+
        '<div class="col-md-12 ">'+
        '<!-- Horizontal Form -->'+
        '<div class="box box-info">'+
        '<div class="box-header with-border">'+
        '<input type="hidden" style="display:none;" id="userid" >'+
        '<h3 class="box-title">ویرایش شماره موبایل </h3>'+
        '</div>'+
        '<!-- /.box-header -->'+
        '<!-- form start -->'+
        '<div class="box-body">'+
        '   <div class="form-group row">'+
        '  <label for="firstname" class="col-sm-2 control-label">شماره موبایل</label>'+
        '<div class="col-sm-3">'+
        '<input type="text" class="form-control" name="mobile" id="mobile"  placeholder="091123456789">'+
        '</div>'+
        '<div class="col-sm-7">'+
        '<label id="namemsg" class="col-sm-12"></label>'+
        '<input type="hidden"  id="checkmobile" value="false" style="display:none;">'+
        '</div>'+
        '</div>'+



        '</div><!-- /.box-body -->'+

        '</div><!-- /.box -->'+
        '</div>'+

        '</div>'+


        '<!-- personal info -->'+
        '<div class="row" >'+
        '<div class="col-md-12 ">'+
        '<!-- Horizontal Form -->'+
        '<div class="box box-info">'+
        '<div class="box-header with-border">'+
            '<input type="hidden" style="display:none;" id="userid" >'+
        '<h3 class="box-title">مشخصات فردی</h3>'+
        '</div>'+
        '<!-- /.box-header -->'+
        '<!-- form start -->'+
        '<div class="box-body">'+
        '   <div class="form-group row">'+
        '  <label for="firstname" class="col-sm-2 control-label">نام <span style="color:#FF0000;">*</span> </label>'+
        '<div class="col-sm-3">'+
        '<input type="text" class="form-control" name="firstname" id="firstname"  tabindex="7" placeholder="نام">'+
        '</div>'+
        '<div class="col-sm-7">'+
        '<label id="namemsg" class="col-sm-12"></label>'+
        '<input type="hidden"  id="checkfirstname" value="false" style="display:none;">'+
        '</div>'+
        '</div>'+

        '<div class="form-group row">'+
        '<label for="lastname" class="col-sm-2 control-label"> <span style="color:#FF0000;">*</span> نام خانوادگی</label>'+
        '<div class="col-sm-3">'+
        '<input type="text" class="form-control" name="lastname" id="lastname"  tabindex="8" placeholder="نام خانوادگی">'+
        '</div>'+
        '<div class="col-sm-7">'+
        '<label id="lastnamemsg" class="col-sm-12"></label>'+
        '<input type="hidden" id="checklastname" value="false" style="display:none;">'+
        '</div>'+
        '</div>'+

        '<div class="form-group row">'+
        '<label for="lastname" class="col-sm-2 control-label">جنسیت</label>'+
        '<div class="col-sm-3">'+
        '<select class="selectpicker" id="sex" data-live-search="false" tabindex="9">'+
        '<option data-tokens="ketchup mustard" value="1">مرد</option>'+
        '<option data-tokens="mustard" value="0">زن</option>'+
        '</select>'+
        '</div>'+
        '<div class="col-sm-1">'+
        '<label id="sexsmsg"></label>'+
        '</div>'+
        '</div>'+

        '<div class="form-group row">'+
        '<label for="email" class="col-sm-2 control-label"  >ایمیل</label>'+
        '<div class="col-sm-3">'+
        '<input type="email" class="form-control" id="email" tabindex="10" placeholder="Email">'+
        '</div>'+
        '<div class="col-sm-7">'+
        '   <label id="emailmsg" class="col-sm-12"></label>'+
        '<input type="hidden" id="checkemail" value="true"'+
        '  </div>'+
        ' </div>'+

        '</div><!-- /.box-body -->'+

        '</div><!-- /.box -->'+
        '</div>'+

        '</div>'+


        '<!-- address info -->'+
        '<div class="row" >'+
        '<div class="col-md-12 ">'+
        '<!-- Horizontal Form -->'+
        '<div class="box box-info">'+
        '<div class="box-header with-border">'+
        '<h3 class="box-title">محل سکونت</h3>'+
        '</div>'+
        '<!-- /.box-header -->'+
        '<!-- form start -->'+
        '<div class="box-body">'+
        '<div class="form-group row">'+
        '<label for="lastname" class="col-sm-2 control-label" >استان</label>'+
        '<div class="col-sm-3">'+
        '<select class="selectpicker" id="state" data-live-search="true" tabindex="11">'+
        '<option data-tokens="ketchup mustard" value="0">استان</option>'+
        '</select>'+
        '</div>'+
        '<div class="col-sm-1">'+
        '<label id="statemsg"></label>'+
        '</div>'+
        '</div>'+

        '<div class="form-group row">'+
        '<label for="lastname" class="col-sm-2 control-label">شهر</label>'+
        '<div class="col-sm-3">'+
        '<select class="selectpicker" id="city" data-live-search="true" tabindex="12">'+
        '<option data-tokens="ketchup mustard" value="0">شهر</option>'+
        '</select>'+
        '</div>'+
        '<div class="col-sm-1">'+
        '<label id="citymsg"></label>'+
        '</div>'+
        '</div>'+

        '<div class="form-group row">'+
        '<label for="lastname" class="col-sm-2 control-label">منطقه</label>'+
        '<div class="col-sm-3">'+
        '<select class="selectpicker" id="region" data-live-search="true" tabindex="13">'+
        '<option data-tokens="ketchup mustard" value="0">منطقه</option>'+
        '</select>'+
        '</div>'+
        '<div class="col-sm-1">'+
        '<label id="regionmsg"></label>'+
        '</div>'+
        '</div>'+

        '<div class="form-group row">'+
        '<label for="email" class="col-sm-2 control-label">آدرس</label>'+
        '<div class="col-sm-3">'+
        '<textarea type="address" class="form-control" name="address" tabindex="13" id="address"  placeholder="آدرس محل سکونت" cols="50" rows="5"> </textarea>'+
        '</div>'+
        '<div class="col-sm-7">'+
        '<label id="addressmsg" class="col-sm-12"></label>'+
        '<input type="hidden" id="checkaddress" value="true" style="display:none;">'+
        '</div>'+
        '</div>'+

        '</div><!-- /.box-body -->'+

        '</div><!-- /.box -->'+
        '</div>'+

        '</div>'+


        '<div class="box-footer row">'+
        '<button type="button" class="btn btn-primary  pull-right col-md-2" onclick=\"switchtoshow()\">انصراف</button>'+
            '<div class="col-md-1"></div>'+
        '<button type="button" class="btn btn-success pull-right col-md-2" onclick=\"editUserProfile()\">ثبت</button>'+
        '</div>'+
        '<!— /.box-footer —>'+
        '</form>';



    $('#password-confirmation').on('change' , function () {
        check_repeat_password( "password-confirmation" , "checkrepassword", "passconfirmmsg", this.value , $('#password').val() );
    });
    $('#password').on('change' , function () {
        general_validation( "password" , "checkpassword", "passwordmsg", this.value , "password" );
    });
    $('#address').on('change' , function () {
        general_validation( "address" , "checkaddress", "addressmsg", this.value , "address" );
    });
    $('#email').on('change' , function () {
        general_validation("email", "checkemail", "emailmsg", this.value , "email" );
    });
    $('#lastname').on('keyup' , function () {
        general_validation( "lastname" , "checklastname", "lastnamemsg", this.value , "name" );
    });
    $('#firstname').on('keyup' , function () {

        general_validation("firstname", "checkfirstname", "namemsg", this.value , "name" );
    });
    $('#mobile').on('change' , function () {
        general_validation( "mobile" , "checkmobile", "mobilemsg", this.value , "mobile" );
    });

    $('#state').on('change' , function () {
        loadCities(this.value);
    });
    $('#city').on('change' , function () {
        loadRegions(this.value);
    });
}

function switchtoshow()
{
    document.getElementById('profile-field-show');
    $('#profile-field-show').css('display','block');
    $('#profile-field-edit').css('display','none');
}
function switchtoedit()
{
    document.getElementById('profile-field-show');
    $('#profile-field-show').css('display','none');
    $('#profile-field-edit').css('display','block');
    document.getElementById('profile-field-edit');
}

// edit user data and profile
function editUserProfile() {  // edit aram 2-8
    // edit both profile and essntial data in one query

    var userid = document.getElementById('userid').value;
    // this update user profile data
    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {

            act: 'edit_user_profile',
            id:userid,
            gender : document.getElementById('sex').value,
            state : document.getElementById('state').value,
            city : document.getElementById('city').value,
            region : document.getElementById('region').value,
            address:document.getElementById('address').value,


            firstname : document.getElementById('firstname').value ,
            lastname : document.getElementById('lastname').value ,
            email : document.getElementById('email').value,
            mobilenumber:document.getElementById('mobile').value


        }, function (data) {
            //console.log("user profile : "+data);
            var obj = jQuery.parseJSON(data);
            if (obj['act'] == 'data'){
                //alert('data');
                //console.log('edit-user-profile: '+ data );
            }
            else if (obj['act'] == 'message') {
                //alert('message');
                console.log("OK" + document.getElementById('userid').value);
                showProfile( userid );
                prepare_message( obj['text'] , 'fa' , obj['title'] );
            }
        })
    );


}
/********************/
// redirect the page to

function Redirect( page ) {
    window.location = page;
}

/*********************/
//refresh the captcha
function refreshLoginCaptcha() {
    $("#captcha_code").attr('src','/View/ajax/captcha_code.php');
}



/*************************/
// check user input to be valid


function validateLoginForm() {
    var valid = true;
    var message = '';

    if(!$("#username").val()) {
        message += '<li>لطفا نام کاربری را پر کنید</li>';
        valid = false;
    }
    if(!$("#password").val()) {
        message += '<li>لطفا رمز عبور را وارد کنید</li>';
        valid = false;
    }
    if(!$("#captcha").val()) {
        message += '<li> لطفا کپچا را وارد کنید</li>';
        valid = false;
    }
    if( !valid )
        showMessageModal( 'warning' , 'هشدار' , message );

    return valid;
}

//__________________________________________________________________ end of login page

//__________________________________________________________________ dashboard page

function logout() {
    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act: 'logout'
        })
    ).done(function (data) {

            //console.log(data);

            var obj = jQuery.parseJSON(data);
            if (obj['act'] == 'data')
            {

                console.log(data);
                prepare_message(obj['data'] , 'fa' , 'پیغام');
                setTimeout(function() { Redirect('/View/index.html'); }, 2000);

            }

            else if (obj['act'] == 'message') {
                console.log(data);
                var tr= null;
                prepare_message(obj['text'] , 'fa' , 'پیغام');
                setTimeout(function() { Redirect('/View/index.html'); }, 2000);
            }
            else {
                // Redirect('/View/index.html');
            }
        }
    );
}
function loadMenu() {  // edit aram 2-8
    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act: 'load_menu'
        } , function (data) {


            var obj = JSON.parse( String(data) );
            //console.log( data );
            //console.log( obj['data'] );



            //call sreate menu function
            createMenu( obj['data'] );

            //generate menu using returned data


    }));
}

//create menu by json data, this menu depth is 2
function createMenu( menuData ) { // edit aram 2-8

    //console.log(menuData);
    if( menuData.constructor === Array )
    {

        menuLength = menuData.length;
        window.MenuItem = '';
        var MenuTree = '';
        window.MenuTreeList ='';

        window.MenuTreeList = '';

        for(var i = 0 ; i < menuLength ; i++ ){
            var tempMenu = menuData[i];
            //console.log("\n tempMenu: "+ menuData[i]['Parent'] + " " +tempMenu.Parent + "  " + i );

            // find parent menu
            if( tempMenu.Parent == "0" )
            {
                // Menu tree is a super menu
                // we find their childs ( submenu )
                //MenuTree = tempMenu;
                MenuTree += '<li class="treeview">'+
                    '<a href="#">'+
                    '<i class="fa fa-angle-left pull-left"></i><i class="fa fa-tasks"></i> <span>'+ tempMenu.Name +'</span>'+
                    '</a>'+
                    '<ul class="treeview-menu">';



                window.MenuItem = '';
                // find childs of this menu
                for(var j = 0 ; j < menuLength ; j++ )
                {


                    var tempMenu2 = menuData[j];

                    if( tempMenu2.Parent == tempMenu.id )
                    {
                        window.MenuItem +='<li><a href='+tempMenu2.Url+'><i class="fa fa-circle-o"></i>'+ tempMenu2.Name +'</a></li>';



                    }

                }

                //console.log('inside for if: MenuItem:' + window.MenuItem);
                //continue;
                //add menu item to menutree
                MenuTree += window.MenuItem;
                MenuTree += '</ul></li>';
                // add menu tree to menu tree list
            }
        }
        window.MenuTreeList += MenuTree;
        window.MenuTreeList += '';
        //console.log(window.MenuTreeList);
        document.getElementById('sidebar-menu').innerHTML = window.MenuTreeList;

    }
    
}


function loadMonthlyStatistic() {
    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), { act: 'get_monthly_statistic' },
        function (data) {

            var obj = jQuery.parseJSON(data);

            var monthlabel = [];
            var monthdata = [];
            //console.log(obj['data']);
            for( var i = 0 ; i < obj['data'].length ; i++ )
            {
                monthlabel[i] = obj['data'][i]['monthName'];
                monthdata[i] = obj['data'][i]['count'];
            }
            //console.log( monthdata);
            //console.log( monthlabel);
           // return obj['data'];

            var areaChartData = {
                labels: monthlabel,
                datasets: [
                    {
                        label: "نمودار آمار ثبت نام کاربران",
                        fillColor: "rgba(210, 214, 222, 1)",
                        strokeColor: "rgba(210, 214, 222, 1)",
                        pointColor: "rgba(210, 214, 222, 1)",
                        pointStrokeColor: "#c1c7d1",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: monthdata
                    }
                ]
            };

            var areaChartOptions = {
                //Boolean - If we should show the scale at all
                showScale: true,
                //Boolean - Whether grid lines are shown across the chart
                scaleShowGridLines: true,
                //String - Colour of the grid lines
                scaleGridLineColor: "rgba(0,0,0,.05)",
                //Number - Width of the grid lines
                scaleGridLineWidth: 1,
                //Boolean - Whether to show horizontal lines (except X axis)
                scaleShowHorizontalLines: true,
                //Boolean - Whether to show vertical lines (except Y axis)
                scaleShowVerticalLines: true,
                //Boolean - Whether the line is curved between points
                bezierCurve: true,
                //Number - Tension of the bezier curve between points
                bezierCurveTension: 0.3,
                //Boolean - Whether to show a dot for each point
                pointDot: true,
                //Number - Radius of each point dot in pixels
                pointDotRadius: 4,
                //Number - Pixel width of point dot stroke
                pointDotStrokeWidth: 1,
                //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
                pointHitDetectionRadius: 20,
                //Boolean - Whether to show a stroke for datasets
                datasetStroke: true,
                //Number - Pixel width of dataset stroke
                datasetStrokeWidth: 2,
                //Boolean - Whether to fill the dataset with a color
                datasetFill: true,
                //String - A legend template
                legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
                //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                maintainAspectRatio: true,
                //Boolean - whether to make the chart responsive to window resizing
                responsive: true
            };


            //-------------
            //- LINE CHART -
            //--------------
            var lineChartCanvas = $("#MonthChart").get(0).getContext("2d");
            var lineChart = new Chart(lineChartCanvas);
            var lineChartOptions = areaChartOptions;
            lineChartOptions.datasetFill = false;
            lineChart.Line(areaChartData, lineChartOptions);

        })
    );
}

function loadweeklyStatistic() {
    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), { act: 'get_weekly_statistic' },
        function (data) {

            var obj = jQuery.parseJSON(data);
            //console.log(obj['data']);

            var week = [];
            var weekdata = [];
            var monthlabel = [];
            for( var i = 0 ; i < obj['data'].length ; i++ )
            {
                monthlabel[i] = obj['data'][i]['monthName'];
                week[i] = "(" + obj['data'][i]['weeks'] + ")" +obj['data'][i]['monthName'];
                weekdata[i] = obj['data'][i]['count'];
            }
            // return obj['data'];

            //console.log(week);
            //console.log(weekdata);

            var areaChartData = {
                labels: week ,
                datasets: [
                    {
                        label: "نمودار آمار ثبت نام کاربران",
                        fillColor: "rgba(210, 214, 222, 1)",
                        strokeColor: "rgba(210, 214, 222, 1)",
                        pointColor: "rgba(210, 214, 222, 1)",
                        pointStrokeColor: "#c1c7d1",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: weekdata
                    }
                ]
            };

            var areaChartOptions = {
                //Boolean - If we should show the scale at all
                showScale: true,
                //Boolean - Whether grid lines are shown across the chart
                scaleShowGridLines: true,
                //String - Colour of the grid lines
                scaleGridLineColor: "rgba(0,0,0,.05)",
                //Number - Width of the grid lines
                scaleGridLineWidth: 1,
                //Boolean - Whether to show horizontal lines (except X axis)
                scaleShowHorizontalLines: true,
                //Boolean - Whether to show vertical lines (except Y axis)
                scaleShowVerticalLines: true,
                //Boolean - Whether the line is curved between points
                bezierCurve: true,
                //Number - Tension of the bezier curve between points
                bezierCurveTension: 0.3,
                //Boolean - Whether to show a dot for each point
                pointDot: true,
                //Number - Radius of each point dot in pixels
                pointDotRadius: 4,
                //Number - Pixel width of point dot stroke
                pointDotStrokeWidth: 1,
                //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
                pointHitDetectionRadius: 20,
                //Boolean - Whether to show a stroke for datasets
                datasetStroke: true,
                //Number - Pixel width of dataset stroke
                datasetStrokeWidth: 2,
                //Boolean - Whether to fill the dataset with a color
                datasetFill: true,
                //String - A legend template
                //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                maintainAspectRatio: true,
                //Boolean - whether to make the chart responsive to window resizing
                responsive: true
            };


            //-------------
            //- LINE CHART -
            //--------------
            var lineChartCanvas = $("#WeekChart").get(0).getContext("2d");
            var lineChart = new Chart(lineChartCanvas);
            var lineChartOptions = areaChartOptions;
            lineChartOptions.datasetFill = false;
            lineChart.Line(areaChartData, lineChartOptions);

        })
    );
}

function loadMonthlyStatisticJalali() {
    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), { act: 'get_monthly_statistic_jalali' },
        function (data) {

            //console.log('loadMonthlyStatisticJalali' +  data );
            var obj = jQuery.parseJSON(data);

            var monthlabel = [];
            var monthdata = [];
            //console.log(obj['data']);
            for( var i = 0 ; i < obj['data'].length ; i++ )
            {
                monthlabel[i] = obj['data'][i]['monthName'];
                monthdata[i] = obj['data'][i]['count'];
            }
            //console.log( monthdata);
            //console.log( monthlabel);
            // return obj['data'];

            var areaChartData = {
                labels: monthlabel,
                datasets: [
                    {
                        label: "نمودار آمار ثبت نام کاربران",
                        fillColor: "rgba(210, 214, 222, 1)",
                        strokeColor: "rgba(210, 214, 222, 1)",
                        pointColor: "rgba(210, 214, 222, 1)",
                        pointStrokeColor: "#c1c7d1",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: monthdata
                    }
                ]
            };

            var areaChartOptions = {
                //Boolean - If we should show the scale at all
                showScale: true,
                //Boolean - Whether grid lines are shown across the chart
                scaleShowGridLines: true,
                //String - Colour of the grid lines
                scaleGridLineColor: "rgba(0,0,0,.05)",
                //Number - Width of the grid lines
                scaleGridLineWidth: 1,
                //Boolean - Whether to show horizontal lines (except X axis)
                scaleShowHorizontalLines: true,
                //Boolean - Whether to show vertical lines (except Y axis)
                scaleShowVerticalLines: true,
                //Boolean - Whether the line is curved between points
                bezierCurve: true,
                //Number - Tension of the bezier curve between points
                bezierCurveTension: 0.3,
                //Boolean - Whether to show a dot for each point
                pointDot: true,
                //Number - Radius of each point dot in pixels
                pointDotRadius: 4,
                //Number - Pixel width of point dot stroke
                pointDotStrokeWidth: 1,
                //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
                pointHitDetectionRadius: 20,
                //Boolean - Whether to show a stroke for datasets
                datasetStroke: true,
                //Number - Pixel width of dataset stroke
                datasetStrokeWidth: 2,
                //Boolean - Whether to fill the dataset with a color
                datasetFill: true,
                //String - A legend template
                legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
                //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                maintainAspectRatio: true,
                //Boolean - whether to make the chart responsive to window resizing
                responsive: true
            };


            //-------------
            //- LINE CHART -
            //--------------
            var lineChartCanvas = $("#MonthChartJalali").get(0).getContext("2d");
            var lineChart = new Chart(lineChartCanvas);
            var lineChartOptions = areaChartOptions;
            lineChartOptions.datasetFill = false;
            lineChart.Line(areaChartData, lineChartOptions);

        })
    );
}

function loadweeklyStatisticJalali() {
    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), { act: 'get_weekly_statistic_jalali' },
        function (data) {

            var obj = jQuery.parseJSON(data);
            //console.log(obj['data']);

            var week = [];
            var weekdata = [];
            var monthlabel = [];
            for( var i = 0 ; i < obj['data'].length ; i++ )
            {
                monthlabel[i] = obj['data'][i]['monthName'];
                week[i] = "(" + obj['data'][i]['weeks'] + ")" +obj['data'][i]['monthName'];
                weekdata[i] = obj['data'][i]['count'];
            }
            // return obj['data'];

            //console.log(week);
            //console.log(weekdata);

            var areaChartData = {
                labels: week ,
                datasets: [
                    {
                        label: "نمودار آمار ثبت نام کاربران",
                        fillColor: "rgba(210, 214, 222, 1)",
                        strokeColor: "rgba(210, 214, 222, 1)",
                        pointColor: "rgba(210, 214, 222, 1)",
                        pointStrokeColor: "#c1c7d1",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: weekdata
                    }
                ]
            };

            var areaChartOptions = {
                //Boolean - If we should show the scale at all
                showScale: true,
                //Boolean - Whether grid lines are shown across the chart
                scaleShowGridLines: true,
                //String - Colour of the grid lines
                scaleGridLineColor: "rgba(0,0,0,.05)",
                //Number - Width of the grid lines
                scaleGridLineWidth: 1,
                //Boolean - Whether to show horizontal lines (except X axis)
                scaleShowHorizontalLines: true,
                //Boolean - Whether to show vertical lines (except Y axis)
                scaleShowVerticalLines: true,
                //Boolean - Whether the line is curved between points
                bezierCurve: true,
                //Number - Tension of the bezier curve between points
                bezierCurveTension: 0.3,
                //Boolean - Whether to show a dot for each point
                pointDot: true,
                //Number - Radius of each point dot in pixels
                pointDotRadius: 4,
                //Number - Pixel width of point dot stroke
                pointDotStrokeWidth: 1,
                //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
                pointHitDetectionRadius: 20,
                //Boolean - Whether to show a stroke for datasets
                datasetStroke: true,
                //Number - Pixel width of dataset stroke
                datasetStrokeWidth: 2,
                //Boolean - Whether to fill the dataset with a color
                datasetFill: true,
                //String - A legend template
                //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                maintainAspectRatio: true,
                //Boolean - whether to make the chart responsive to window resizing
                responsive: true
            };


            //-------------
            //- LINE CHART -
            //--------------
            var lineChartCanvas = $("#WeekChartJalali").get(0).getContext("2d");
            var lineChart = new Chart(lineChartCanvas);
            var lineChartOptions = areaChartOptions;
            lineChartOptions.datasetFill = false;
            lineChart.Line(areaChartData, lineChartOptions);

        })
    );
}

/*
//______________________________
 */
//////////////////////////////////////
/*
 load main role page function
 */
////////////////////////////////////////


function loadMainRolePage() {

    var obj = null;

    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act:'get_all_roles'
        })
    ).done(function (data) {

            obj = jQuery.parseJSON(data);
            console.log("&&&&&&&&&&&&&"+data);
            //console.log("************"+keys);


        var tableplace="mytable";
        var tableid= "maintable";
        var tablebodiid="gridview";
        var havereport=false;
        var haveoperation=true;
        var header = ["ردیف", "نام دسترسی", "توضیحات","عملیات اجرایی"];
        var footer = ["ردیف", "نام دسترسی", "توضیحات","عملیات اجرایی"];

        var btno0= {type:"button", value:"حذف", class:"btn btn-xs btn-danger",triger:"deleteRole" ,function:"prepareConfirmModal",modal:"data-toggle='modal' data-target='#confirmModal'"};
        var btno1= {type:"button", value:"اکشن ها", class:"btn btn-xs btn-success", function:"loadRoleAction",modal:"data-toggle='modal' data-target='#mainModal'"};
        var btno2= {type:"button", value:"کاربران با این دسترسی", class:"btn btn-xs btn-primary", function:"loadUserwithSpecialRole",modal:"data-toggle='modal' data-target='#mainModal'"};

        var reportbtn=null;

        var operationbtn={0:btno0 , 1:btno1, 2:btno2};
        var params=null;

            if (obj['act'] == 'data') {

                //var keys = Object.keys(obj['data'][0]);
                //var acts = obj['data'];
                /*
                 create parameters for create table include:( tableplace , tableid, tablecontentid,arrays for header,array of footer,tablebody,havereport,reportbtn,haveoperation,operationbtn)
                 */



                tableCreator2(tableplace , tableid , tablebodiid , header , obj['data'] , footer , havereport , reportbtn , haveoperation , operationbtn,params);


            }

            else if (obj['act'] == 'message') {


                prepare_message(obj['text'], 'fa' , obj['title'] );

                tableCreator2(tableplace , tableid , tablebodiid , header , null , footer , havereport , reportbtn , haveoperation , operationbtn,params);

                return null;
            }
        }
    );


}


//////////////////////////////////////
/*
 load main role page function
 */
////////////////////////////////////////


function loadMainRoleGroupPage() {

    var obj = null;

    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act:'get_all_groups'
        })
    ).done(function (data) {

            obj = jQuery.parseJSON(data);
            //console.log("&&&&&&&&&&&&&"+data);
            //console.log("************"+keys);


            var tableplace="mytable";
            var tableid= "maintable";
            var tablebodiid="gridview";
            var havereport=false;
            var haveoperation=true;
            var header = ["ردیف", "نام گروه دسترسی", "توضیحات","عملیات اجرایی"];
            var footer = ["ردیف", "نام گروه دسترسی", "توضیحات","عملیات اجرایی"];

            var btno0= {type:"button", value:"حذف", class:"btn btn-xs btn-danger",triger:"deleteGroup" ,function:"prepareConfirmModal",modal:"data-toggle='modal' data-target='#confirmModal'"};
            var btno1= {type:"button", value:"دسترسی ها", class:"btn btn-xs btn-success", function:"loadRoleAction",modal:"data-toggle='modal' data-target='#mainModal'"};
            var btno2= {type:"button", value:"کاربران با این گروه", class:"btn btn-xs btn-primary", function:"loadUserwithSpecialGroup",modal:"data-toggle='modal' data-target='#mainModal'"};


            var reportbtn=null;

            var operationbtn={0:btno0 , 1:btno1 ,2:btno2};
            var params=null;

            if (obj['act'] == 'data') {

                //var keys = Object.keys(obj['data'][0]);
                //var acts = obj['data'];
                /*
                 create parameters for create table include:( tableplace , tableid, tablecontentid,arrays for header,array of footer,tablebody,havereport,reportbtn,haveoperation,operationbtn)
                 */



                tableCreator2(tableplace , tableid , tablebodiid , header , obj['data'] , footer , havereport , reportbtn , haveoperation , operationbtn,params);


            }

            else if (obj['act'] == 'message') {


                prepare_message(obj['text'], 'fa' , obj['title'] );

                tableCreator2(tableplace , tableid , tablebodiid , header , null , footer , havereport , reportbtn , haveoperation , operationbtn,params);

                return null;
            }
        }
    );


}


////////////////////////////////////////
/*
 prepare argument for set role function
 */
///////////////////////////////////////


function prepareArgsForSetRole(){
    var arg1=document.getElementById('roleName').value;
    var arg2=document.getElementById('roleDescription').value;
    document.getElementById('roleName').value="";
    document.getElementById('roleDescription').value="";
    setRole(arg1 , arg2);
}

////////////////////////////////////////
/*
 prepare argument for set role group function
 */
///////////////////////////////////////

function prepareArgsForSetRoleGroup(){
    var arg1=document.getElementById('roleGroupName').value;
    var arg2=document.getElementById('roleGroupDescription').value;
    document.getElementById('roleGroupName').value="";
    document.getElementById('roleGroupDescription').value="";
    setRoleGroup(arg1 , arg2);
}







/////////////////////////////////////////
/*
 set role function
 */
/////////////////////////////////////////
function setRole(arg1 , arg2) {





    var obj = null;
    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act:'set_role',
            name:arg1,
            description:arg2
        })
    ).done(function (data) {
            //console.log("&&&&&&&&&&&&&"+data);

            obj = jQuery.parseJSON(data);
            //var keys = Object.keys(obj['data'][0]);
            //console.log("ZZZZZZZZZZZZZZZZZZZZZZZ"+obj['data']);
            //console.log("************"+keys);
            //var table=document.getElementById('maintable');

            if (obj['act'] == 'data') {





            }

            else if (obj['act'] == 'message') {



                var el = document.getElementById('maintable_wrapper');
                el.parentElement.removeChild(el);//innerHTML="secondtable_wrapper";

                $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
                        act:'get_all_roles'
                    })
                ).done(function (data) {

                        obj = jQuery.parseJSON(data);
                        console.log("&&&&&&&&&&&&&"+data);
                        //console.log("************"+keys);
                        if (obj['act'] == 'data') {

                            var keys = Object.keys(obj['data'][0]);
                            var acts = obj['data'];
                            /*
                             create parameters for create table include:( tableplace , tableid, tablecontentid,arrays for header,array of footer,tablebody,havereport,reportbtn,haveoperation,operationbtn)
                             */
                            var tableplace="mytable";
                            var tableid= "maintable";
                            var tablebodiid="gridview";
                            var havereport=false;
                            var haveoperation=true;
                            var header = ["ردیف", "نام دسترسی", "توضیحات","عملیات اجرایی"];
                            var footer = ["ردیف", "نام دسترسی", "توضیحات","عملیات اجرایی"];

                            var btno0= {type:"button", value:"حذف", class:"btn btn-xs btn-danger",triger:"deleteRole" ,function:"prepareConfirmModal",modal:"data-toggle='modal' data-target='#confirmModal'"};
                            var btno1= {type:"button", value:"اکشن ها", class:"btn btn-xs btn-success", function:"loadRoleAction",modal:"data-toggle='modal' data-target='#mainModal'"};
                            var btno2= {type:"button", value:"کاربران با این دسترسی", class:"btn btn-xs btn-primary", function:"loadUserwithSpecialRole",modal:"data-toggle='modal' data-target='#mainModal'"};

                            var reportbtn=null;

                            var operationbtn={0:btno0 , 1:btno1 ,2:btno2};
                            var params=null;


                            tableCreator2(tableplace , tableid , tablebodiid , header , obj['data'] , footer , havereport , reportbtn , haveoperation , operationbtn,params);


                        }

                        else if (obj['act'] == 'message') {
                            //prepare_message(obj['text'], 'fa' , obj['title'] );
                            return null;
                        }
                    }
                );

                console.log("%%%%%%%%%%%%%%%%"+obj['text']);
                prepare_message(obj['text'], 'fa' , obj['title'] );
                //showMessageModal('warning', obj['title'], obj['text']);
                return null;
            }
        }
    );
}

/////////////////////////////////////////
/*
 set role group function
 */
/////////////////////////////////////////
function setRoleGroup(arg1 , arg2) {





    var obj = null;
    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act:'set_group',
            name:arg1,
            description:arg2
        })
    ).done(function (data) {
            console.log("&&&&&&&&&&&&&"+data);

            obj = jQuery.parseJSON(data);
            //var keys = Object.keys(obj['data'][0]);
            //console.log("ZZZZZZZZZZZZZZZZZZZZZZZ"+obj['data']);
            //console.log("************"+keys);
            //var table=document.getElementById('maintable');

            if (obj['act'] == 'data') {





            }

            else if (obj['act'] == 'message') {



                var el = document.getElementById('maintable_wrapper');
                el.parentElement.removeChild(el);



                $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
                        act:'get_all_groups'
                    })
                ).done(function (data) {

                        obj = jQuery.parseJSON(data);
                        console.log("&&&&&&&&&&&&&"+data);
                        //console.log("************"+keys);
                        if (obj['act'] == 'data') {

                            var keys = Object.keys(obj['data'][0]);
                            var acts = obj['data'];
                            /*
                             create parameters for create table include:( tableplace , tableid, tablecontentid,arrays for header,array of footer,tablebody,havereport,reportbtn,haveoperation,operationbtn)
                             */
                            var tableplace="mytable";
                            var tableid= "maintable";
                            var tablebodiid="gridview";
                            var havereport=false;
                            var haveoperation=true;
                            var header = ["ردیف", "نام گروه دسترسی", "توضیحات","عملیات اجرایی"];
                            var footer = ["ردیف", "نام گروه دسترسی", "توضیحات","عملیات اجرایی"];

                            var btno0= {type:"button", value:"حذف", class:"btn btn-xs btn-danger",triger:"deleteGroup" ,function:"prepareConfirmModal",modal:"data-toggle='modal' data-target='#confirmModal'"};
                            var btno1= {type:"button", value:"دسترسی ها", class:"btn btn-xs btn-success", function:"loadRole",modal:"data-toggle='modal' data-target='#mainModal'"};
                            var btno2= {type:"button", value:"کاربران با این گروه", class:"btn btn-xs btn-primary", function:"loadUserwithSpecialGroup",modal:"data-toggle='modal' data-target='#mainModal'"};

                            var reportbtn=null;

                            var operationbtn={0:btno0 , 1:btno1 ,2:btno2};
                            var params=null;


                            tableCreator2(tableplace , tableid , tablebodiid , header , obj['data'] , footer , havereport , reportbtn , haveoperation , operationbtn,params);


                        }

                        else if (obj['act'] == 'message') {
                            //prepare_message(obj['text'], 'fa' , obj['title'] );
                            return null;
                        }
                    }
                );

                console.log("%%%%%%%%%%%%%%%%"+obj['text']);
                prepare_message(obj['text'], 'fa' , obj['title'] );
                //showMessageModal('warning', obj['title'], obj['text']);
                return null;
            }
        }
    );
}


//////////////////////////////////////////////
/*
 delete role function
 */
////////////////////////////////////////////

function deleteRole(params) {
    console.log("params"+params[0]);

    var deleteid=params['0'];

    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act: 'delete_role',
            id :deleteid

        }, function (data) {
            console.log("data====================="+data);


            var obj = jQuery.parseJSON(data);

            if (obj['act'] == 'data'){

                var e2 = document.getElementById('maintable_wrapper');
                console.log(e2.value);

                e2.parentElement.removeChild(e2);

                var obj = null;
                $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
                        act:'get_all_roles'
                    })
                ).done(function (data) {

                        obj = jQuery.parseJSON(data);
                        //var keys = Object.keys(obj['data'][0]);
                        //console.log("&&&&&&&&&&&&&"+obj['data'][0]);
                        //console.log("************"+keys);


                    var tableplace="mytable";
                    var tableid= "maintable";
                    var tablebodiid="gridview";
                    var havereport=false;
                    var haveoperation=true;
                    var header = ["ردیف", "نام دسترسی", "توضیحات","عملیات اجرایی"];
                    var footer = ["ردیف", "نام دسترسی", "توضیحات","عملیات اجرایی"];

                    var btno0= {type:"button", value:"حذف", class:"btn btn-xs btn-danger",triger:"deleteRole" ,function:"prepareConfirmModal",modal:"data-toggle='modal' data-target='#confirmModal'"};
                    var btno1= {type:"button", value:"اکشن ها", class:"btn btn-xs btn-success", function:"loadRoleAction",modal:"data-toggle='modal' data-target='#mainModal'"};
                    var btno2= {type:"button", value:"کاربران با این دسترسی", class:"btn btn-xs btn-primary", function:"loadUserwithSpecialRole",modal:"data-toggle='modal' data-target='#mainModal'"};

                    var reportbtn=null;

                    var operationbtn={0:btno0 , 1:btno1 ,2:btno2};
                    var params=null;


                        if (obj['act'] == 'data') {

                            var acts = obj['data'];
                            /*
                             create parameters for create table include:( tableplace , tableid, tablecontentid,arrays for header,array of footer,tablebody,havereport,reportbtn,haveoperation,operationbtn)
                             */



                            tableCreator2(tableplace , tableid , tablebodiid , header , obj['data'] , footer , havereport , reportbtn , haveoperation , operationbtn,params);

                        }

                        else if (obj['act'] == 'message') {

                            tableCreator2(tableplace , tableid , tablebodiid , header , null , footer , havereport , reportbtn , haveoperation , operationbtn,params);

                            prepare_message(obj['text'], 'fa' , obj['title'] );
                            //showMessageModal('warning', obj['title'], obj['text']);
                            return null;
                        }
                    }
                );

            }
            else if (obj['act'] == 'message') {
                //alert('message');
                //console.log("OK");
                //prepare_message( obj['text'] , 'fa' , obj['title'] );
            }
        })
    );


}



//////////////////////////////////////////////
/*
 delete role function
 */
////////////////////////////////////////////

function deleteGroup(params) {
    console.log("params"+params[0]);

    var deleteid=params['0'];

    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act: 'delete_group',
            id :deleteid

        }, function (data) {
            console.log("data====================="+data);


            var obj = jQuery.parseJSON(data);

            if (obj['act'] == 'data'){

                var e2 = document.getElementById('maintable_wrapper');
                console.log(e2.value);

                e2.parentElement.removeChild(e2);

                var obj = null;
                $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
                        act:'get_all_groups'
                    })
                ).done(function (data) {

                        obj = jQuery.parseJSON(data);
                        //var keys = Object.keys(obj['data'][0]);
                        //console.log("&&&&&&&&&&&&&"+obj['data'][0]);
                        //console.log("************"+keys);


                        var tableplace="mytable";
                        var tableid= "maintable";
                        var tablebodiid="gridview";
                        var havereport=false;
                        var haveoperation=true;
                        var header = ["ردیف", "نام گروه دسترسی", "توضیحات","عملیات اجرایی"];
                        var footer = ["ردیف", "نام گروه دسترسی", "توضیحات","عملیات اجرایی"];

                        var btno0= {type:"button", value:"حذف", class:"btn btn-xs btn-danger",triger:"deleteGroup" ,function:"prepareConfirmModal",modal:"data-toggle='modal' data-target='#confirmModal'"};
                        var btno1= {type:"button", value:"دسترسی ها", class:"btn btn-xs btn-success", function:"loadRole",modal:"data-toggle='modal' data-target='#mainModal'"};
                        var btno2= {type:"button", value:"کاربران با این گروه", class:"btn btn-xs btn-primary", function:"loadUserwithSpecialGroup",modal:"data-toggle='modal' data-target='#mainModal'"};

                        var reportbtn=null;

                        var operationbtn={0:btno0 , 1:btno1 ,2:btno2};
                        var params=null;


                        if (obj['act'] == 'data') {

                            var acts = obj['data'];
                            /*
                             create parameters for create table include:( tableplace , tableid, tablecontentid,arrays for header,array of footer,tablebody,havereport,reportbtn,haveoperation,operationbtn)
                             */



                            tableCreator2(tableplace , tableid , tablebodiid , header , obj['data'] , footer , havereport , reportbtn , haveoperation , operationbtn,params);

                        }

                        else if (obj['act'] == 'message') {

                            tableCreator2(tableplace , tableid , tablebodiid , header , null , footer , havereport , reportbtn , haveoperation , operationbtn,params);

                            prepare_message(obj['text'], 'fa' , obj['title'] );
                            //showMessageModal('warning', obj['title'], obj['text']);
                            return null;
                        }
                    }
                );

            }
            else if (obj['act'] == 'message') {
                //alert('message');
                //console.log("OK");
                //prepare_message( obj['text'] , 'fa' , obj['title'] );
            }
        })
    );


}



function loadRoleAction(rid){
    document.getElementById('mainModalHeaderLabel').innerHTML='<h2>اکشن ها</h2>'+ '<br>'+ '<br>';
    document.getElementById('mainModalContent').innerHTML='<div class="box box-info">'+
        '<div class="box-header with-border">'+
        '<h3 class="box-title">افزودن اکشن به دسترسی </h3>'+
        '<br>'+'<br>'+
        '<div class="box-body">'+
        '<input type="button" id="allActionCollapse" onclick="loadAllRemainingActions('+rid+')" class="btn btn-dropbox btn-flat btn-info " value="ایجاد" data-toggle="collapse" data-target="#createActionCollapse">'+
        '<br>'+'<br>'+
        '<div id="createActionCollapse" class="collapse">'+
        '<select id="selectaction" class="col-md-2 selectpicker input-sm">'+
        '<option value="0">اکشن</option>'+
        '</select>'+
        '<button class="btn btn-default" onclick="setRoleAction('+rid+')">' +"تایید"+'</button>'+
        '<br>'+
        '<br>'+
        '<br>'+ '<br>'+
        '</div>'+
        '</div>'+
        '</div>'+
        '</div> ';




    var obj = null;
    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act:'get_role_action',
            id:rid
        })
    ).done(function (data) {

        console.log("actions========================="+data);


            obj = jQuery.parseJSON(data);





        /*
         create parameters for create table include:( tableplace , tableid, tablecontentid,arrays for header,array of footer,tablebody,havereport,reportbtn,haveoperation,operationbtn)
         */
        var tableplace="mainModalContent";
        var tableid= "secondtable";
        var tablebodiid="secondcontent";
        var havereport=false;
        var haveoperation=true;
        var header = ["ردیف" ,"نام اکشن","توضیحات","عملیات اجرایی"];
        var footer = ["ردیف" ,"نام اکشن","توضیحات" ,"عملیات اجرایی"];
        var btno0= {type:"button", value:"حذف", class:"btn btn-xs btn-danger",triger:"deleteRoleAction" , function:"prepareConfirmModal",modal:"data-toggle='modal' data-target='#confirmModal'"};
        var reportbtn=null;
        var operationbtn={0:btno0};
        var params=[rid];
        //return;
            //var keys = Object.keys(obj['data'][0]);
            //console.log("ZZZZZZZZZZZZZZZZZZZZZZZ"+obj['data']);
            //console.log("************"+keys);
            if (obj['act'] == 'data') {

               // var acts = obj['data'];




                tableCreator2(tableplace , tableid , tablebodiid , header , obj['data'] , footer , havereport , reportbtn , haveoperation , operationbtn,params);

                /*
                 execute data table javascript file
                 */

                //$('#maintable').DataTable( {
                //   dom: 'Bfrtip',
                //   buttons: [
                //         'copy', 'csv', 'excel', 'pdf', 'print'
                //   ]
                // } );

            }

            else if (obj['act'] == 'message') {
                tableCreator2(tableplace , tableid , tablebodiid , header , null , footer , havereport , reportbtn , haveoperation , operationbtn,params);
                prepare_message(obj['text'], 'fa' , obj['title'] );
                //showMessageModal('warning', obj['title'], obj['text']);
                return null;
            }
        }
    );


}





/////////////////////////////////////////////////////
/*
load all roles that are belong to this group
 */


function loadGroupRole(gid){
    document.getElementById('mainModalHeaderLabel').innerHTML='<h2>دسترسی ها</h2>'+ '<br>'+ '<br>';
    document.getElementById('mainModalContent').innerHTML='<div class="box box-info">'+
        '<div class="box-header with-border">'+
        '<h3 class="box-title">افزودن دسترسی به گروه </h3>'+
        '<br>'+'<br>'+
        '<div class="box-body">'+
        '<input type="button" id="allActionCollapse" onclick="loadAllRemainingRoleForGroup('+gid+')" class="btn btn-dropbox btn-flat btn-info " value="ایجاد" data-toggle="collapse" data-target="#createRoleCollapse">'+
        '<br>'+'<br>'+
        '<div id="createRoleCollapse" class="collapse">'+
        '<select id="selectroleforgroup" class="col-md-2 selectpicker input-sm">'+
        '<option value="0">اکشن</option>'+
        '</select>'+
        '<button class="btn btn-default" onclick="setGroupRole('+gid+')">' +"تایید"+'</button>'+
        '<br>'+
        '<br>'+
        '<br>'+ '<br>'+
        '</div>'+
        '</div>'+
        '</div>'+
        '</div> ';




    var obj = null;
    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act:'get_group_role',
            id:gid
        })
    ).done(function (data) {

            console.log("actions========================="+data);


            obj = jQuery.parseJSON(data);





            /*
             create parameters for create table include:( tableplace , tableid, tablecontentid,arrays for header,array of footer,tablebody,havereport,reportbtn,haveoperation,operationbtn)
             */
            var tableplace="mainModalContent";
            var tableid= "secondtable";
            var tablebodiid="secondcontent";
            var havereport=false;
            var haveoperation=true;
            var header = ["ردیف" ,"نام دسترسی","توضیحات","عملیات اجرایی"];
            var footer = ["ردیف" ,"نام دسترسی","توضیحات" ,"عملیات اجرایی"];
            var btno0= {type:"button", value:"حذف", class:"btn btn-xs btn-danger",triger:"deleteGroupRole" , function:"prepareConfirmModal",modal:"data-toggle='modal' data-target='#confirmModal'"};
            var reportbtn=null;
            var operationbtn={0:btno0};
            var params=[rid];
            //return;
            //var keys = Object.keys(obj['data'][0]);
            //console.log("ZZZZZZZZZZZZZZZZZZZZZZZ"+obj['data']);
            //console.log("************"+keys);
            if (obj['act'] == 'data') {

                // var acts = obj['data'];




                tableCreator2(tableplace , tableid , tablebodiid , header , obj['data'] , footer , havereport , reportbtn , haveoperation , operationbtn,params);

                /*
                 execute data table javascript file
                 */

                //$('#maintable').DataTable( {
                //   dom: 'Bfrtip',
                //   buttons: [
                //         'copy', 'csv', 'excel', 'pdf', 'print'
                //   ]
                // } );

            }

            else if (obj['act'] == 'message') {
                tableCreator2(tableplace , tableid , tablebodiid , header , null , footer , havereport , reportbtn , haveoperation , operationbtn,params);
                prepare_message(obj['text'], 'fa' , obj['title'] );
                //showMessageModal('warning', obj['title'], obj['text']);
                return null;
            }
        }
    );


}


/////////////////////////////////////////
/*
 set role action function
 */
/////////////////////////////////////////
function setRoleAction(rid) {

    var actions= document.getElementById("selectaction");
    var selectedidx =  actions.selectedIndex;
    var aname = actions.options[selectedidx].data;
    var aid = actions.options[selectedidx].value;
    var adesc=actions.options[selectedidx].text;


    var obj = null;
    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act:'set_role_action',
            roleid:rid,
            actionid:aid
        })
    ).done(function (data) {
            console.log("&&&&&&&&&&&&&"+data);

            obj = jQuery.parseJSON(data);
            //var keys = Object.keys(obj['data'][0]);
            //console.log("ZZZZZZZZZZZZZZZZZZZZZZZ"+obj['data']);
            //console.log("************"+keys);
            var table=document.getElementById('secondtable');

            if (obj['act'] == 'data') {


                var table = $('#secondtable').DataTable();
                if(table.rows()!=null){
                    var m = table.rows().indexes();
                    var maxim = Math.max.apply( Math , m )+2;
                    if(!isFinite(maxim)){
                        maxim=1;
                    }
                }
                else{
                    var maxim = 1;
                }
                var parameters=[rid , aid];

                table.row.add($( '<tr>'+'<td>'+(maxim)+'</td>'+
                    '<td>'+aname+'</td>'+
                        '<td>'+adesc+'</td>'+
                    '<td><div class="btn-group btn-group-xs row"><input type="button" value="حذف" class="btn btn-xs btn-danger" onclick="prepareConfirmModal(['+parameters + '],'+"deleteRoleAction"+')" data-toggle="modal" data-target="#confirmModal" />'+
                    '</div></td>'
                )).draw();
                table.columns.adjust().draw();


                //loadGroup(uid);

            }

            else if (obj['act'] == 'message') {
                console.log("%%%%%%%%%%%%%%%%"+obj['text']);
                // prepare_message(obj['text'], 'fa' , obj['title'] );
                //showMessageModal('warning', obj['title'], obj['text']);
                return null;
            }
        }
    );




}


/////////////////////////////////////////
/*
 set group role function
 */
/////////////////////////////////////////
function setGroupRole(gid) {

    var roles= document.getElementById("selectroleforgroup");
    var selectedidx =  roles.selectedIndex;
    var rname = roles.options[selectedidx].data;
    var rid = roles.options[selectedidx].value;
    var rdesc=roles.options[selectedidx].text;


    var obj = null;
    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act:'set_group_role',
            groupid:gid,
            roleid:rid
        })
    ).done(function (data) {
            console.log("&&&&&&&&&&&&&"+data);

            obj = jQuery.parseJSON(data);
            //var keys = Object.keys(obj['data'][0]);
            //console.log("ZZZZZZZZZZZZZZZZZZZZZZZ"+obj['data']);
            //console.log("************"+keys);
            var table=document.getElementById('secondtable');

            if (obj['act'] == 'data') {


                var table = $('#secondtable').DataTable();
                if(table.rows()!=null){
                    var m = table.rows().indexes();
                    var maxim = Math.max.apply( Math , m )+2;
                    if(!isFinite(maxim)){
                        maxim=1;
                    }
                }
                else{
                    var maxim = 1;
                }
                var parameters=[gid , rid];

                table.row.add($( '<tr>'+'<td>'+(maxim)+'</td>'+
                    '<td>'+rname+'</td>'+
                    '<td>'+rdesc+'</td>'+
                    '<td><div class="btn-group btn-group-xs row"><input type="button" value="حذف" class="btn btn-xs btn-danger" onclick="prepareConfirmModal(['+parameters + '],'+"deleteGroupRole"+')" data-toggle="modal" data-target="#confirmModal" />'+
                    '</div></td>'
                )).draw();
                table.columns.adjust().draw();


                //loadGroup(uid);

            }

            else if (obj['act'] == 'message') {
                console.log("%%%%%%%%%%%%%%%%"+obj['text']);
                // prepare_message(obj['text'], 'fa' , obj['title'] );
                //showMessageModal('warning', obj['title'], obj['text']);
                return null;
            }
        }
    );




}


////////////////////////////////////////////////
///////////////////////////////////////////////delete group role
function deleteGroupRole( params ){
    var gid=params['0'];
    var rid=params['1'];


    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act: 'delete_group_role',
            groupid :gid,
            roleid:rid

        }, function (data) {
            console.log("data====================="+data);

            var obj = jQuery.parseJSON(data);

            if (obj['act'] == 'data'){

                //alert(obj['data']);
                var table = $('#secondtable').DataTable();
                //table.clear();
                var el = document.getElementById('secondtable_wrapper');
                el.parentElement.removeChild(el);//innerHTML="secondtable_wrapper";

                //var sectab = document.getElementById('secondtable_wrapper').innerHTML="";






                $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
                        act:'get_group_role',
                        id:gid
                    })
                ).done(function (data) {
                        //console.log("&&&&&&&&&&&&&"+data);

                        obj = jQuery.parseJSON(data);
                        //var keys = Object.keys(obj['data'][0]);
                        //console.log("ZZZZZZZZZZZZZZZZZZZZZZZ"+obj['data']);
                        //console.log("************"+keys);


                        /*
                         create parameters for create table include:( tableplace , tableid, tablecontentid,arrays for header,array of footer,tablebody,havereport,reportbtn,haveoperation,operationbtn)
                         */


                    var tableplace="mainModalContent";
                    var tableid= "secondtable";
                    var tablebodiid="secondcontent";
                    var havereport=false;
                    var haveoperation=true;
                    var header = ["ردیف" ,"نام دسترسی","توضیحات","عملیات اجرایی"];
                    var footer = ["ردیف" ,"نام دسترسی","توضیحات" ,"عملیات اجرایی"];
                    var btno0= {type:"button", value:"حذف", class:"btn btn-xs btn-danger",triger:"deleteGroupRole" , function:"prepareConfirmModal",modal:"data-toggle='modal' data-target='#confirmModal'"};
                    var reportbtn=null;
                    var operationbtn={0:btno0};
                    var params=[gid];


                        if (obj['act'] == 'data') {

                            //var acts = obj['data'];




                            tableCreator2(tableplace , tableid , tablebodiid , header , obj['data'] , footer , havereport , reportbtn , haveoperation , operationbtn,params);

                            /*
                             execute data table javascript file
                             */

                            //$('#maintable').DataTable( {
                            //   dom: 'Bfrtip',
                            //   buttons: [
                            //         'copy', 'csv', 'excel', 'pdf', 'print'
                            //   ]
                            // } );

                        }

                        else if (obj['act'] == 'message') {
                            tableCreator2(tableplace , tableid , tablebodiid , header , null , footer , havereport , reportbtn , haveoperation , operationbtn,params);
                            prepare_message(obj['text'], 'fa' , obj['title'] );
                            //showMessageModal('warning', obj['title'], obj['text']);
                            return null;
                        }
                    }
                );



                //table.row( $(this).parents('tr') ).remove().draw();
                //table.row('.selected').remove().draw( true );
                //loadMainPage();
                // var table = document.getElementById('maintable');

            }
            else if (obj['act'] == 'message') {
                //alert('message');
                console.log("not OK");
                //prepare_message( obj['text'] , 'fa' , obj['title'] );
            }
        })
    );
}





/*
 ///////////__________   advanced search users
 */
function search_users() {



    var obj = null;

    $.when($.get("/View/ajax/index.php?_=" + new Date().getTime(), {
            act:'search_users',
            firstname: document.getElementById('firstname').value,
            lastname: document.getElementById('lastname').value,
            email: document.getElementById('email').value,
            mobilenumber:document.getElementById('mobile').value,
            gender: 1,
            StateId : null,
            CityId : null,
            RegionId : null,
            Address : null,
            isLogin : null,
            isActive : null,
            isDelete : null,
            CreateBy : null,
            startCreateDate:  document.getElementById('startCreateDate').value,
            endCreateDate:   document.getElementById('endCreateDate').value ,
            startLoginDate:  document.getElementById('startLoginDate').value ,
            endLoginDate: document.getElementById('endLoginDate').value ,
            startEditPasswordDate: document.getElementById('startEditPasswordDate').value ,
            endEditPasswordDate: document.getElementById('endEditPasswordDate').value
        })
    ).done(function (data) {
            //console.log("&&&&&&&&&&&&&"+data);

            obj = jQuery.parseJSON(data);
            //console.log("************"+keys);



        document.getElementById("returnbutton").innerHTML = '<input type="button" class="btn btn-default " onclick="loadMainPage();" value="جستجو &times;" >';
        var el = document.getElementById('maintable_wrapper');
        el.parentElement.removeChild(el);//innerHTML="secondtable_wrapper";

            var tableplace="mytable";
            var tableid= "maintable";
            var tablebodiid="gridview";
            var havereport=true;
            var haveoperation=true;
            var header = ["ردیف", "نام", "نام خانوادگی", "شماره همراه", "ایمیل", "گزارشات", "عملیات اجرایی"];
            var footer = ["ردیف", "نام", "نام خانوادگی", "شماره همراه", "ایمیل", "گزارشات", "عملیات اجرایی"];
            var btnr0= {type:"button", value:"کسب و کار", class:"btn btn-xs btn-success", function:"default",modal:"data-toggle='modal' data-target='#mainModal'"};
            var btnr1= {type:"button", value:"پروفایل", class:"btn btn-xs btn-warning", function:"showProfile",modal:"data-toggle='modal' data-target='#mainModal'"};
            var btnr2= {type:"button", value:"باشگاه", class:"btn btn-xs btn-primary", function:"default",modal:"data-toggle='modal' data-target='#mainModal'"};
            var btno0= {type:"button", value:"حذف", class:"btn btn-xs btn-danger",triger:"deleteUser" ,function:"prepareConfirmModal",modal:"data-toggle='modal' data-target='#confirmModal'"};
            var btno1= {type:"button", value:"دسترسی", class:"btn btn-xs btn-warning", function:"loadRole",modal:"data-toggle='modal' data-target='#mainModal'"};
            var btno2= {type:"button", value:"گروه دسترسی", class:"btn btn-xs btn-info",function:"loadGroup",modal:"data-toggle='modal' data-target='#mainModal'"};
            var btno3= {type:"button", value:"فعال سازی", class:"btn btn-xs btn-default", function:"userActivation",modal:"data-toggle='modal' data-target='#messageModal'"};
            var btno4= {type:"button", value:"ورود", class:"btn btn-xs btn-primary", function:"default",modal:"data-toggle='modal' data-target='#mainModal'"};
            var reportbtn={0:btnr0 , 1:btnr1 ,2:btnr2};
            var operationbtn={0:btno0 , 1:btno1 ,2:btno2,3:btno3,4:btno4};
            var params=null;

            if (obj['act'] == 'data') {

                //var keys = Object.keys(obj['data'][0]);
                //var acts = obj['data'];
                /*
                 create parameters for create table include:( tableplace , tableid, tablecontentid,arrays for header,array of footer,tablebody,havereport,reportbtn,haveoperation,operationbtn)
                 */


                tableCreator2(tableplace , tableid , tablebodiid , header , obj['data'] , footer , havereport , reportbtn , haveoperation , operationbtn,params);


            }

            else if (obj['act'] == 'message') {
                console.log("*****");
                prepare_message(obj['text'], 'fa' , obj['title'] );
                tableCreator2(tableplace , tableid , tablebodiid , header , null , footer , havereport , reportbtn , haveoperation , operationbtn,params);

                return null;
            }
        }
    );














}

/*
show adavnced search modal
 */
function showAdvancedSearch(){
    document.getElementById("mainModalHeaderLabel").innerHTML='<h2>جستجوی پیشرفته کاربران</h2>'+ '<br>'+ '<br>';
    document.getElementById("mainModalContent").innerHTML = '<form class="form-horizontal">'+


        '<div class="row container-fluid" >'+

        '<div class="col-md-12 ">'+
        '<!-- Horizontal Form -->'+
        '<div class="box box-info">'+
        '<div class="box-header with-border">'+
        '<h3 class="box-title">مشخصات</h3>'+
        '</div>'+
        '<!-- /.box-header -->'+
        '<div class="box-body">'+
        '<div class="form-group">'+
        '<label for="mobile" class="col-sm-2 control-label">تلفن همراه</label>'+
        '<div class="col-sm-3">'+
        '<input type="text" class="form-control" tabindex="1" id="mobile" placeholder="شماره تلفن همراه">'+
    '</div>'+
    '<div class=" col-sm-1">'+
    //'<input type="checkbox" value="">'+
    '</div>'+

    '</div>'+


    '<div class="form-group">'+
    '<label for="firstname" class="col-sm-2 control-label">نام</label>'+
    '<div class="col-sm-3">'+
    '<input type="name" class="form-control" name="firstname" id="firstname" tabindex="7" placeholder="نام">'+
    '</div>'+
    '<div class=" col-sm-1">'+
    //'<input type="checkbox" value="">'+
    '</div>'+
    '</div>'+



    '<div class="form-group">'+
    '<label for="lastname" class="col-sm-2 control-label"> نام خانوادگی</label>'+
    '<div class="col-sm-3">'+
    '<input type="name" class="form-control" name="lastname" id="lastname" tabindex="8" placeholder="نام خانوادگی">'+
    '</div>'+
    '<div class=" col-sm-1">'+
   // '<input type="checkbox" value="">'+
    '</div>'+
    '</div>'+



    '<div class="form-group">'+
    '<label for="lastname" class="col-sm-2 control-label">جنسیت</label>'+
    '<div class="col-sm-3">'+
    '<select class="selectpicker" id="sex" data-live-search="false" tabindex="9">'+
    '<option data-tokens="ketchup mustard" value="1">مرد</option>'+
    '<option data-tokens="mustard" value="0">زن</option>'+
    '</select>'+
    '</div>'+
    '<div class=" col-sm-1">'+
    '<input type="checkbox" value="">'+
    '</div>'+
    '</div>'+



    '<div class="form-group">'+
    '<label for="email" class="col-sm-2 control-label">ایمیل</label>'+
    '<div class="col-sm-3">'+
    '<input type="email" class="form-control" id="email" tabindex="10" placeholder="Email">'+
    '</div>'+
    '<div class=" col-sm-1">'+
    //'<input type="checkbox" value="">'+
    '</div>'+
    '</div>'+




    '<div class="form-group">'+
    '<label for="clubname" class="col-sm-2 control-label"> نام باشگاه</label>'+
    '<div class="col-sm-3">'+
    '<input type="name" class="form-control" name="lastname" id="clubname" tabindex="8" placeholder="نام باشگاه">'+
    '</div>'+
    '<div class=" col-sm-1">'+
   // '<input type="checkbox" value="">'+
    '</div>'+
    '</div>'+



    '</div><!-- /.box-body -->'+
    '<div class="box-footer">'+

    '</div><!-- /.box-footer -->'+
    '</div><!-- /.box -->'+
    '<!-- general form elements disabled -->'+
    '<!-- /.box -->'+
    '</div>'+
    '</div>'+



    '<div class="row" >'+

    '<div class="col-md-12 ">'+
    '<!-- Horizontal Form -->'+
    '<div class="box box-info">'+
    '<div class="box-header with-border">'+
    '<h3 class="box-title">تاریخ ایجاد</h3>'+
    '</div>'+
    '<!-- /.box-header -->'+
    '<div class="box-body">'+


    '<div class="form-group">'+
    '<label for="firstname" class="col-sm-2 control-label">از تاریخ</label>'+
    '<div class="col-sm-2">'+
    '<input type="text" class="form-control" placeholder="روز-ماه-سال" id="startCreateDate" /><br />'+
    '</div>'+
    '<div class=" col-sm-1">'+
    '<input type="checkbox" value="">'+
    '</div>'+
    '</div>'+



    '<div class="form-group">'+
    '<label for="firstname" class="col-sm-2 control-label">تا تاریخ</label>'+
    '<div class="col-sm-2">'+
    '<input type="text" class="form-control" placeholder="روز-ماه-سال" id="endCreateDate" /><br />'+
    '</div>'+
    '<div class=" col-sm-1">'+
    '<input type="checkbox" value="">'+
    '</div>'+
    '</div>'+



    '</div><!-- /.box-body -->'+

    '</div><!-- /.box -->'+
    '<!-- general form elements disabled -->'+
    '<!-- /.box -->'+
    '</div>'+
    '</div>'+


    '<div class="row" >'+

    '<div class="col-md-12 ">'+
    '<!-- Horizontal Form -->'+
    '<div class="box box-info">'+
    '<div class="box-header with-border">'+
    '<h3 class="box-title">تاریخ ورود به سیستم</h3>'+
    '</div>'+
    '<!-- /.box-header -->'+
    '<div class="box-body">'+


    '<div class="form-group">'+
    '<label for="firstname" class="col-sm-2 control-label">از تاریخ</label>'+
    '<div class="col-sm-2">'+
    '<input type="text" class="form-control" placeholder="روز-ماه-سال" id="startLoginDate" /><br />'+
    '</div>'+
    '<div class=" col-sm-1">'+
    '<input type="checkbox" value="">'+
    '</div>'+
    '</div>'+



    '<div class="form-group">'+
    '<label for="firstname" class="col-sm-2 control-label">تا تاریخ</label>'+
    '<div class="col-sm-2">'+
    '<input type="text" class="form-control" placeholder="روز-ماه-سال" id="endLoginDate" /><br />'+
    '</div>'+
    '<div class=" col-sm-1">'+
    '<input type="checkbox" value="">'+
    '</div>'+
    '</div>'+



    '</div><!-- /.box-body -->'+

    '</div><!-- /.box -->'+
    '<!-- general form elements disabled -->'+
    '<!-- /.box -->'+
    '</div>'+
    '</div>'+



    '<div class="row" >'+

    '<div class="col-md-12 ">'+
    '<!-- Horizontal Form -->'+
    '<div class="box box-info">'+
    '<div class="box-header with-border">'+
    '<h3 class="box-title">تاریخ آخرین به روز رسانی گذرواژه</h3>'+
    '</div>'+
    '<!-- /.box-header -->'+
    '<div class="box-body">'+



    '<div class="form-group">'+
    '<label for="firstname" class="col-sm-2 control-label">از تاریخ</label>'+
    '<div class="col-sm-2">'+
    '<input type="text" class="form-control" placeholder="روز-ماه-سال" id="startEditPasswordDate" /><br />'+
    '</div>'+
    '<div class=" col-sm-1">'+
    '<input type="checkbox" value="">'+
    '</div>'+
    '</div>'+



    '<div class="form-group">'+
    '<label for="firstname" class="col-sm-2 control-label">تا تاریخ</label>'+
    '<div class="col-sm-2">'+
    '<input type="text" class="form-control" placeholder="روز-ماه-سال" id="endEditPasswordDate" /><br />'+
    '</div>'+
    '<div class=" col-sm-1">'+
    '<input type="checkbox" value="">'+
    '</div>'+
    '</div>'+



    '</div><!-- /.box-body -->'+

    '</div><!-- /.box -->'+
    '<!-- general form elements disabled -->'+
    '<!-- /.box -->'+
    '</div>'+
    '</div>'+
    '<div class="box-footer row">'+
    '<button type="reset" class="btn btn-primary  pull-right col-md-2"  >انصراف</button>'+
    '<div class="col-md-1"></div>'+
    '<button type="button" class="btn btn-success pull-right col-md-2" onclick="search_users()" data-toggle="modal" data-target="#mainModal">ثبت</button>'+
    '</div>'+


    '</form>';

    $("#startCreateDate").persianDatepicker({formatDate: "YYYY-0M-0D"});
    $("#endCreateDate").persianDatepicker({formatDate: "YYYY-0M-0D"});

    $("#startLoginDate").persianDatepicker({formatDate: "YYYY-0M-0D"});
    $("#endLoginDate").persianDatepicker({formatDate: "YYYY-0M-0D"});

    $("#startEditPasswordDate").persianDatepicker({formatDate: "YYYY-0M-0D"});
    $("#endEditPasswordDate").persianDatepicker({formatDate: "YYYY-0M-0D"});




    $('#password-confirmation').on('change' , function () {
        check_repeat_password( "password-confirmation" , "checkrepassword", "passconfirmmsg", this.value , $('#password').val() );
    });
    $('#password').on('change' , function () {
        general_validation( "password" , "checkpassword", "passwordmsg", this.value , "password" );
    });
    $('#address').on('change' , function () {
        general_validation( "address" , "checkaddress", "addressmsg", this.value , "address" );
    });
    $('#email').on('change' , function () {
        general_validation("email", "checkemail", "emailmsg", this.value , "email" );
    });
    $('#lastname').on('keyup' , function () {
        general_validation( "lastname" , "checklastname", "lastnamemsg", this.value , "name" );
    });
    $('#firstname').on('keyup' , function () {

        general_validation("firstname", "checkfirstname", "namemsg", this.value , "name" );
    });
    $('#mobile').on('change' , function () {
        general_validation( "mobile" , "checkmobile", "mobilemsg", this.value , "mobile" );
    });
    $('#state').on('change' , function () {
        loadCities(this.value);
    });
    $('#city').on('change' , function () {
        loadRegions(this.value);
    });



}




function setWarningFillForm( msg , lang , labelid ) {
    $.when($.get("/View/lang/lang.xml" , function (xml) {
        var xmlDoc = xml;

        var x = xmlDoc.getElementsByTagName(msg); // get all msg tags as array, and x = first msg tag

        //console.log(x);
        if( x.length == 0 )
        {

            show_modal( msgt , msg  , 'fa');
            return;
        }
        var persianMsg = x[0].firstElementChild.innerHTML; // get persian data of a message
        var englishMsg = x[0].firstElementChild.nextElementSibling.innerHTML; // set y = x's child node
        var type = x[0].lastElementChild.innerHTML;

        //alert(type);

        console.log( persianMsg );
        switch (lang )
        {
            case 'fa':
                document.getElementById( labelid).innerHTML = '<span style="color:#FF0000">' + persianMsg + '</span>';

                break;
            case 'en':
                document.getElementById( labelid).value =  englishMsg;

                break;
            default:
                document.getElementById( labelid).value =  persianMsg;
                break;
        }


    }));

}