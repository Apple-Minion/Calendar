<?php
    class Calendar{

        public function __construct(){
            date_default_timezone_set("America/New_York");
            $this->month = date('m');
            $this->year = date('Y');
            $this->total = (int)date('t');
        }

        public function print(){
            if(isset($_POST['month']) && isset($_POST['year'])){
                $this->month = $_POST['month'];
                $this->year = $_POST['year'];
                $this->total = (int)date('t', strtotime($this->year.'-'.$this->month.'-01'));
            }

            $print =
            '<br><br><div id="calendar">'.
                $this->head().
                '<div class="box-content">'.
                    '<ul class="label">';

            foreach($this->DAYS as $day)
                $print .= '<li>'.$day.'</li>';

            $print .=
                    '</ul>'.
                    '<div class-"clear"></div>'.
                    '<form action="day.php" method="POST">'.
                        '<ul class="dates">'.
                            $this->days().
                        '</ul>'.
                        '<input type="hidden" name="month" value="'.$this->month.'">'.
                        '<input type="hidden" name="year" value="'.$this->year.'">'.
                    '</form>'.
                    '<div class="clear"></div>'.
                '</div>'.
            '</div>';
            echo $print;
        }

        private function head(){
            if($this->month == 12){
                $nextMonth = 1;
                $nextYear = $this->year + 1;
            }
            else{
                $nextMonth = $this->month + 1;
                $nextYear = $this->year;
            }

            if($this->month == 1){
                $prevMonth = 12;
                $prevYear = $this->year - 1;
            }
            else{
                $prevMonth = $this->month - 1;
                $prevYear = $this->year;
            }
            
            $head = 
            '<div class="box">'.
                '<div class="header">'.
                    '<form class="prev" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'" method="POST">'.
                        '<button type="submit" class="flat">&#10094;</button>'.
                        '<input type="hidden" name="month" value="'.$prevMonth.'">'.
                        '<input type="hidden" name="year" value="'.$prevYear.'">'.
                    '</form>'.
                    '<span class="title">'.date('M, Y',strtotime($this->year.'-'.$this->month.'-1')).'</span>'.
                    '<form class="next" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'" method="POST">'.
                        '<button type="submit" class="flat">&#10095;</button>'.
                        '<input type="hidden" name="month" value="'.$nextMonth.'">'.
                        '<input type="hidden" name="year" value="'.$nextYear.'">'.
                    '</form>'.
                '</div>'.
            '</div>';

            return $head;
        }

        private function days(){
            $start = date('N',strtotime($this->year.'-'.$this->month.'-01'))%7;
            $end = date('N',strtotime($this->year.'-'.$this->month.'-'.$this->total))%7;
            $length = ($start > $end ? 1:0) + ($this->total%7 == 0 ? 0:1) + (int)($this->total/7);
            $length *= 7;

            $print = '';
            for($i = 0; $i < $length; $i++){
                $print .= $this->day($i, $length, $start, $end);
            }
            return $print;
        }

        private function day($index, $length, $start, $end){
            if(($index < $start) || ((int)($index/7) == (int)(($length-1)/7) && $index%7 > $end)){
                $cellNum = null;
                $cell = '<li></li>';
            }
            else{
                $cellNum = $index - $start + 1;
                $cell = '<li><input type="submit" name="day" value="'.$cellNum.'" class="flat"></li>';
            }
            return $cell;
        }

        private $DAYS = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        private $month;
        private $year;
        private $total;
    }
?>


