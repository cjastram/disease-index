<pre><?php

$data = yaml_parse(file_get_contents("master-list.yaml"));

class Databank
{
    public $questions = array();

    private function parse($array)
    {
        foreach( $array as $key => $value )
        {
            if( is_array( $value ) )
            {
                if( array_key_exists("Natural History", $value) )
                {
                    $question = new Question($key, $value);
                    array_push($this->questions, $question);
                }
                else
                {
                    $this->parse($value);
                }
            }
            else
            {
                # What even goes here who knows
            }
        }
    }

    public function __construct($array)
    {
        $this->parse($array);
    }
}

class Spinner
{
    private $options = array();

    public function __construct() { }
    public function add($option, $likelihood)
    {
        $ubound = $this->get_ubound();
        $ubound += $likelihood;
        array_push($this->options, array($ubound, $option));
    }
    private function get_ubound()
    {
        $ubound = 0;
        if (sizeof( $this->options ) )
        {
            $last = end($this->options);
            $ubound = $last[0];
        }
        return $ubound;
    }
    private function check()
    {
        $ubound = $this->get_ubound();
        if( abs(1-$ubound) > 0.01 )
        {
            print "--> ubound != 1\n";
        }
    }

    public function get()
    {
        $this->check();
        $rand = mt_rand() / mt_getrandmax();
        for( $i=sizeof($this->options)
    }
}

class Question
{
    private $age = null;
    private $gender = null;
    public function __construct($condition, $data)
    {
        foreach( $data as $key => $value )
        {
            switch( $key )
            {
            case "age":
                list( $start, $end ) = explode("-", $value);
                $ages = range($start, $end);
                $this->age = new Spinner();
                foreach( $ages as $age )
                {
                    $this->age->add($age, 1/sizeof($ages));
                }
                break;
            case "gender-ratio":
                list( $m, $f ) = explode("-", $value);
                $total = intval($m) + intval($f);
                $this->gender = new Spinner();
                $this->gender->add("male", $m/$total);
                $this->gender->add("female", $f/$total);
                break;
            case "Natural History":
            case "Clinical Presentation":
            case "Tests":
            case "Treatment":
            case "Management":
            case "Prevention":
                break;
            default:
                print "--> unhandled data field: $key\n";
            }
        }
    }

    public function present()
    {
        #var_dump($this->age);
        if( ! is_null( $this->age ) )
        {
            $age = $this->age->get();
        }
    }
}

$databank = new Databank($data);

foreach( $databank->questions as $question )
{
    print_r($question->present());
}

?></pre>
<html>
<head>
<title>Disease Index Quiz</title>
</head>
<body>

<h1>Disease Index Quiz</h1>

</body>
</html>
