<!-- From https://www.startutorial.com/articles/view/how-to-build-a-web-calendar-in-php -->
<style>
    div#calendar{
        margin: 0px auto;
        padding: 0px;
        width: 602px;
        font-family:Helvetica, "Times New Roman", Times, serif;
    }

    div#calendar div.box{
        position: relative;
        top: 0px;
        left: 0px;
        width: 100%;
        height: 40px;
        background-color: #800404;
    }

    div#calendar div.header{
        line-height: 40px;  
        vertical-align: middle;
        position: absolute;
        left: 11px;
        top: 0px;
        width: 582px;
        height: 40px;   
        text-align: center;
    }

    div#calendar div.header form.prev,div#calendar div.header form.next{ 
        position: absolute;
        margin: 0px !important;
        padding: 0px !important;
        max-width: initial !important;
        top: 0px;   
        height: 0px;
        display: block;
        cursor: pointer;
        text-decoration: none;
        color: #FFF;
    }

    div#calendar div.header form.prev button,div#calendar div.header form.next button{
        position: absolute;
        top: 10px;
    }

    div#calendar div.header span.title{
        color: #FFF;
        font-size: 18px;
    }

    div#calendar div.header form.prev{
        left: 0px;
    }
    
    div#calendar div.header form.next{
        right: 0px;
    }

    div#calendar div.header form.prev button{
        left: 0px;
    }
    
    div#calendar div.header form.next button{
        right: 0px;
    }

    div#calendar div.box-content{
        border: 1px solid #800404;
        border-top: none;
    }
    div#calendar div.box-content form{
        margin: 0px !important;
        padding: 0px !important;
        max-width: initial !important;  
    }
    
    div#calendar ul.label{
        float: left;
        margin: 0px;
        padding: 0px;
        margin-top: 5px;
        margin-left: 5px;
    }

    div#calendar ul.label li{
        margin: 0px;
        padding: 0px;
        margin-right: 5px;  
        float: left;
        list-style-type: none;
        width: 80px;
        height: 40px;
        line-height: 40px;
        vertical-align: middle;
        text-align: center;
        color: #000;
        font-size: 15px;
        background-color: transparent;
    }

    div#calendar ul.dates{
        float: left;
        margin: 0px;
        padding: 0px;
        margin-left: 5px;
        margin-bottom: 5px;
    }

    div#calendar ul.dates li{
        margin: 0px;
        padding: 0px;
        margin-right: 5px;
        margin-top: 5px;
        line-height: 80px;
        vertical-align: middle;
        float: left;
        list-style-type: none;
        width: 80px;
        height: 80px;
        font-size: 25px;
        background-color: #800404;
        color: #000;
        text-align: center; 
    }

    div#calendar ul.dates li input[type="submit"]{
        font-size: 25px;
        width: 70px;
        height: 70px;
        padding: 1px;
    }

    .flat{
        border: 0;
        background: none;
        box-shadow: none;
        border-radius: 0px;
    }

    :focus{
        outline:none;
    }
    
    div.clear{
        clear:both;
    }
</style>
