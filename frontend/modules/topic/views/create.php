<script type="text/javascript">
    $(function() {
        $('a.submit').click(function() {
            alert('hehe');
            $('#createForm').submit();
            return false;
        });
    });
</script>

<style>
    .red { color: red }
</style>

<script>
    $(function() {
        //flash('error', 'test slash js');
        //flash('info', {title:'title notice js', body:'body js'});


    })
</script>

<div class="row-fluid">

    <div class="span12">
        <form id="createForm" method="POST" action="">
            <div class="well">
                <h4>New topic</h4>
<!--                <input style="float:left;margin: 5px 15px 10px 15px" type="text" class="span5" placeholder="title"/>
                <input style="float:left;margin: 5px 0px 0 0px" type="text" class="span5" placeholder="http://"/>-->
                <input name="title" style="float:left;margin: 5px 15px 10px 0px" type="text" class="span12" placeholder="Topic title "/>
                <div class="span11 red">dsdfsdfsdfdf</div>
                <div style="clear:both"></div>

                <link rel="stylesheet" type="text/css" href="/public/plugins/wmd/wmd.css" />
                <script type="text/javascript" src="/public/plugins/wmd/showdown.js"></script>

                <div id="wmd-button-bar" class="wmd-panel"></div>
                <textarea name="body" id="wmd-input" class="wmd-panel" placeholder="Topic description"></textarea>
                <br/>
                <div id="wmd-preview" class="wmd-panel wmd-preview"></div>
                <div class="span11 red">sdfsdfdf sdfdsfsfsdf sdf</div>

                <br/>
                <h4>Tags</h4>
                <select name="tag[]" data-placeholder="Your Favorite Football Teams" class="chzn-select span12" multiple tabindex="6">
                    <option value=""></option>
                    <option selected="selected">Dallas Cowboys</option>
                    <option selected="selected">New York Giants</option>
                    <option>Philadelphia Eagles</option>
                    <option>Washington Redskins</option>
                    <optgroup label="NFC NORTH">
                        <option selected="selected">Chicago Bears</option>
                        <option>Detroit Lions</option>
                        <option>Green Bay Packers</option>
                        <option>Minnesota Vikings</option>
                    </optgroup>
                    <optgroup label="NFC SOUTH">
                        <option>Atlanta Falcons</option>
                        <option>Carolina Panthers</option>
                        <option>New Orleans Saints</option>
                        <option>Tampa Bay Buccaneers</option>
                    </optgroup>
                    <optgroup label="NFC WEST">
                        <option>Arizona Cardinals</option>
                        <option>St. Louis Rams</option>
                        <option>San Francisco 49ers</option>
                        <option>Seattle Seahawks</option>
                    </optgroup>
                    <optgroup label="AFC EAST">
                        <option>Buffalo Bills</option>
                        <option>Miami Dolphins</option>
                        <option>New England Patriots</option>
                        <option>New York Jets</option>
                    </optgroup>
                    <optgroup label="AFC NORTH">
                        <option>Baltimore Ravens</option>
                        <option>Cincinnati Bengals</option>
                        <option>Cleveland Browns</option>
                        <option>Pittsburgh Steelers</option>
                    </optgroup>
                    <optgroup label="AFC SOUTH">
                        <option>Houston Texans</option>
                        <option>Indianapolis Colts</option>
                        <option>Jacksonville Jaguars</option>
                        <option>Tennessee Titans</option>
                    </optgroup>
                    <optgroup label="AFC WEST">
                        <option>Denver Broncos</option>
                        <option>Kansas City Chiefs</option>
                        <option>Oakland Raiders</option>
                        <option>San Diego Chargers</option>
                    </optgroup>
                </select>
                <div class="span11 red">sdfsdfdf sdfdsfsfsdf sdf</div>
                <div style="clear:both"></div>
                <br/>
                <div>
                    <input name="submit" type="submit" class="btn btn-large" value="Create"/>
                </div>
                <!--<div id="wmd-output" class="wmd-panel"></div>-->


                <script type="text/javascript" src="/public/plugins/wmd/wmd.js"></script>

            </div>
        </form>
    </div>
</div>
