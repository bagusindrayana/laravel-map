<?php
namespace Bagusindrayana\LaravelMap;

use Exception;
use phpDocumentor\Reflection\Types\Boolean;
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\BinaryOp\Div;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Expr\BinaryOp\Greater;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\Minus;
use PhpParser\Node\Expr\BinaryOp\Mul;
use PhpParser\Node\Expr\BinaryOp\Plus;
use PhpParser\Node\Expr\BinaryOp\Smaller;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\PostInc;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\UnaryMinus;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Echo_;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\ElseIf_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\NodeDumper;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use ReflectionFunction;
use PhpParser\ParserFactory;
use phptojs\JsPrinter\JsPrinter;
use phptojs\JsPrinter\XD;
use ReflectionMethod;

class Js
{   
    public $extra;
    private $index = 0;
    private $semicolon = true;

    public function __construct() {
        
    }

    public function cleanScript($script)
    {
        return trim(preg_replace('/\s+/'," ",$script));
    }

    public function if($check,$fun)
    {
        if(is_array($check)){
            $this->extra .= "if(".$check['name']."){";
        } else {
            $this->extra .= "if($check){";
        }
            $fun($this);
        $this->extra .= "}";
    }

    public function var($name,$value = null)
    {
        $var = "var ".$name.(($value)?json_encode($value):"");
        $var;
    }

    
  
    public function loop($loops,$attr = null,$index = 0)
    {   
        
        if(!is_array($loops)){
            $loops = [$loops];
        }
        $this->index = $index;
       
        foreach ($loops as $kl => $loop) {
            
            if($loop instanceof Function_){
                $this->extra .= 'function ';
                if(isset($loop->name)){
                    $this->loop($loop->name);
                }
                $this->extra .= '(';
                $this->semicolon = false;
                if(isset($loop->params)){
                    $this->loop($loop->params);
                }
                $this->extra .= ')';
                $this->semicolon = true;
                $this->extra .= "{\r\n";
                if($loop->stmts){
                    $this->loop($loop->stmts);
                }
                $this->extra .= ";\r\n}\r\n";
            } else if($loop instanceof StaticCall){
                
                if(isset($loop->class)){
                    $this->loop($loop->class);
                }
                $this->extra .= '.';
                if(isset($loop->name)){
                    $this->loop($loop->name);
                }
                $this->extra .= '(';
                $this->semicolon = false;
                if(isset($loop->args)){
                    $this->loop($loop->args);
                }
                $this->extra .= ')';
              
            } else if($loop instanceof Expression){
                
                
                if($loop->expr){
                   
                    $this->loop($loop->expr);
                }
            } else if($loop instanceof Assign){
               
                if($loop->var instanceof Variable){
                    $this->extra .= "var ".$loop->var->name." = ";
                }
                $this->semicolon = false;
                
                if($loop->expr){
                    $this->loop($loop->expr);
                }
                
                $this->extra .= ";\r\n";

                

                
            } else if($loop instanceof Variable){
                $this->extra .= $loop->name;
                
            } else if($loop instanceof Plus){
                if(!$loop->left instanceof Variable && !$loop->left instanceof LNumber){
                    if(isset($loop->left->left)){
                        $this->extra .= "(";
                    }
                    $this->loop($loop->left);
                    if(isset($loop->left->left)){
                        $this->extra .= ")";
                    }
                } else {
                    $this->extra .= (@$loop->left->name ?? @$loop->left->value);
                }
                $this->extra .= "+";

                if(!$loop->right instanceof Variable && !$loop->right instanceof LNumber){
                    $this->loop($loop->right);
                } else {
                    $this->extra .= (@$loop->right->name ?? @$loop->right->value);
                }
            } else if($loop instanceof Mul){
                if(!$loop->left instanceof Variable && !$loop->left instanceof LNumber){
                    if(isset($loop->left->left)){
                        $this->extra .= "(";
                    }
                    $this->loop($loop->left);
                    if(isset($loop->left->left)){
                        $this->extra .= ")";
                    }
                } else {
                    $this->extra .= (@$loop->left->name ?? @$loop->left->value);
                }
                $this->extra .= "*";
                if(!$loop->right instanceof Variable && !$loop->right instanceof LNumber){
                    $this->loop($loop->right);
                } else {
                    $this->extra .= (@$loop->right->name ?? @$loop->right->value);
                }
            } else if($loop instanceof Minus){
                if(!$loop->left instanceof Variable && !$loop->left instanceof LNumber){
                    if(isset($loop->left->left)){
                        $this->extra .= "(";
                    }
                    $this->loop($loop->left);
                    if(isset($loop->left->left)){
                        $this->extra .= ")";
                    }
                } else {
                    $this->extra .= (@$loop->left->name ?? @$loop->left->value);
                }
                $this->extra .= "-";
                if(!$loop->right instanceof Variable && !$loop->right instanceof LNumber){
                    $this->loop($loop->right);
                } else {
                    $this->extra .= (@$loop->right->name ?? @$loop->right->value);
                }
            } else if($loop instanceof Div){
                if(!$loop->left instanceof Variable && !$loop->left instanceof LNumber){
                    if(isset($loop->left->left)){
                        $this->extra .= "(";
                    }
                    $this->loop($loop->left);
                    if(isset($loop->left->left)){
                        $this->extra .= ")";
                    }
                } else {
                    $this->extra .= (@$loop->left->name ?? @$loop->left->value);
                }
                $this->extra .= "/";
                if(!$loop->right instanceof Variable && !$loop->right instanceof LNumber){
                    $this->loop($loop->right);
                } else {
                    $this->extra .= (@$loop->right->name ?? @$loop->right->value);
                }
            } else if($loop instanceof MethodCall){
                $this->semicolon = true;
                if(isset($loop->var)){
                   
                    if($loop->var instanceof Variable){
                        
                        $this->loop($loop->var,@$attr);
                    }  else {
                        $this->semicolon = true;
                        $this->loop($loop->var);
                    }
                    
                    
                }
                $this->extra .= ".";
                if(is_object($loop->name)){
                    $this->loop($loop->name,@$attr);
                    
                }
                $this->extra .= "(";
                if(isset($loop->args) && is_array($loop->args)){
                    $this->loop($loop->args,@$attr);
                }
                $this->extra .= ")";
                if($this->semicolon){
                    $this->extra .= ";\r\n";
                }
                
            } else if($loop instanceof Arg){
                if($this->index > 0){
                    $this->extra .= ",";
                }
                if(is_object($loop->name)){
                   $this->loop($loop->name,@$attr,$this->index);
                }
                if(is_object($loop->value)){
                   $this->loop($loop->value,@$attr,$this->index);
                }
                
            } else if($loop instanceof PropertyFetch){
                if($loop->var){
                    $this->loop($loop->var);
                } 
                $this->extra .= ".";
                if($loop->name){
                    $this->loop($loop->name);
                } 
            } else if($loop instanceof Identifier){
                if($loop->name){
                    $this->extra .= $loop->name;
                } 
            } else if($loop instanceof String_){
                $this->extra .= "`$loop->value`";
            } else if($loop instanceof Array_){
                
                $this->extra .= @$attr[0] ?? "{";
                if(isset($loop->items)){
                    $this->loop($loop->items);
                }
                $this->extra .= @$attr[1] ?? "}";
                
                
            } else if($loop instanceof ArrayItem){
                if($this->index > 0){
                    $this->extra .= ",";
                    
                }
                if(isset($loop->key)){
                    $this->loop($loop->key,null,$this->index);
                    $this->extra .= ":";
                }
                
                if(isset($loop->value)){
                    $this->loop($loop->value,null,$this->index);
                    
                }
                
                
            } else if($loop instanceof Return_){
                $this->extra .= "return ";
                $this->loop($loop->expr);
            } else if($loop instanceof New_){
                if(isset($loop->class)){
                    
                    if(isset($loop->class->parts)){
                       
                        if(in_array("JsArray",$loop->class->parts)){
                           
                            if(isset($loop->args)){
                                $this->loop($loop->args,["[","]"]);
                            }
                        }
                        
                    }
                }
            } else if($loop instanceof Name){
                
                if(isset($loop->parts)){
                    $this->extra .= implode(",",$loop->parts);
                }
                
            } else if($loop instanceof FuncCall){
                if(isset($loop->name)){
                    
                    switch ($loop->name->parts[0]) {
                        case 'count':
                            //$this->loop($loop->name,@$attr);
                            $this->semicolon = false;
                            // $this->extra .= "(";
                            if(isset($loop->args)){
                                $this->loop($loop->args,@$attr);
                            }
                            $this->extra .= ".length";
                            $this->semicolon = true;
                            break;
                        
                        default:
                            
                            $this->loop($loop->name,@$attr);
                            $this->semicolon = false;
                            $this->extra .= "(";
                            if(isset($loop->args)){
                                $this->loop($loop->args,@$attr);
                            }
                            $this->extra .= ")";
                            $this->semicolon = true;
                            break;
                    }
                }
                
                
                
            } else if($loop instanceof ArrayDimFetch){
                if(isset($loop->var)){
                    $this->loop($loop->var,@$attr);
                }
                if(isset($loop->dim)){
                    $this->extra .= "[";
                    $this->loop($loop->dim,@$attr);
                    $this->extra .= "]";
                }
            } else if($loop instanceof LNumber || $loop instanceof DNumber || $loop instanceof Boolean){
                if(isset($loop->value)){
                    $this->extra .= $loop->value;
                }
            } else if($loop instanceof If_){
                $this->semicolon = false;
                $this->extra .= "if(";
                if(isset($loop->cond)){
                    $this->loop($loop->cond,@$attr);
                }
                $this->extra .= "){";
                $this->semicolon = true;
                if(isset($loop->stmts)){
                    $this->loop($loop->stmts,@$attr);
                }
                
                $this->extra .= "}";
                if(isset($loop->else)){
                    $this->loop($loop->else,@$attr);
                }
                if(isset($loop->elseif)){
                    $this->loop($loop->elseif,@$attr);
                }
                
            } else if($loop instanceof Else_){
                
                $this->extra .= "else {";
                if(isset($loop->stmts)){
                    $this->loop($loop->stmts,@$attr);
                }
                $this->extra .= "}";
                
            } else if($loop instanceof ElseIf_){
                $this->semicolon = false;
                $this->extra .= "else if(";
                if(isset($loop->cond)){
                    $this->loop($loop->cond,@$attr);
                }
                $this->extra .= "){";
                $this->semicolon = true;
                if(isset($loop->stmts)){
                    $this->loop($loop->stmts,@$attr);
                }
                $this->extra .= "}";
                
            } else if($loop instanceof BooleanOr){
                
                if(isset($loop->left)){
                    $this->loop($loop->left,@$attr);
                }
                $this->extra .= " || ";
                if(isset($loop->right)){
                    $this->loop($loop->right,@$attr);
                }
            } else if($loop instanceof BooleanAnd){
                
                if(isset($loop->left)){
                    $this->loop($loop->left,@$attr);
                }
                $this->extra .= " && ";
                if(isset($loop->right)){
                    $this->loop($loop->right,@$attr);
                }
            } else if($loop instanceof BooleanNot){
                if(isset($loop->expr)){
                    $this->extra .= "!";
                    $this->loop($loop->expr);
                }
            } else if($loop instanceof UnaryMinus){
               
                if(isset($loop->expr)){
                    $this->extra .= "-";
                    $this->loop($loop->expr);
                }
            } else if($loop instanceof Smaller){
                if(isset($loop->left)){
                    $this->loop($loop->left);
                }
                $this->extra .= " < ";
                if(isset($loop->right)){
                    $this->loop($loop->right);
                }
            }

            else if($loop instanceof Greater){
                if(isset($loop->left)){
                    $this->loop($loop->left);
                }
                $this->extra .= " > ";
                if(isset($loop->right)){
                    $this->loop($loop->right);
                }
            }

            else if($loop instanceof Equal){
                if(isset($loop->left)){
                    $this->loop($loop->left);
                }
                $this->extra .= " == ";
                if(isset($loop->right)){
                    $this->loop($loop->right);
                }
            } else if($loop instanceof Identical){
                if(isset($loop->left)){
                    $this->loop($loop->left);
                }
                $this->extra .= " === ";
                if(isset($loop->right)){
                    $this->loop($loop->right);
                }
            } else if($loop instanceof Concat){
                if(isset($loop->left)){
                    $this->loop($loop->left);
                }
                $this->extra .= "+";
                if(isset($loop->right)){
                    $this->loop($loop->right);
                }
            }

            else if($loop instanceof ConstFetch){
                if(isset($loop->name)){
                    $this->loop($loop->name);
                }
            } else if($loop instanceof Closure){
                $this->extra .= 'function ';
                $this->extra .= '(';
                if(isset($loop->params)){
                    $this->loop($loop->params);
                }
                $this->extra .= ')';

                $this->extra .= '{';
                if($loop->stmts){
                    $this->loop($loop->stmts);
                }
                $this->extra .= '}';
            } else if($loop instanceof Param){
                if($this->index > 0){
                    $this->extra .= ",";
                }
                if(is_object($loop->var)){
                   $this->loop($loop->var);
                }

                if(is_object($loop->default)){
                    $this->extra .= "=";
                    $this->loop($loop->default);
                 }
                
                
            } else if($loop instanceof Class_){
                $this->extra .= "class ";
                if($loop->name){
                    $this->loop($loop->name);
                }
                $this->extra .= " {\r\n";
                if($loop->stmts){
                    $this->loop($loop->stmts);
                }
                $this->extra .= "}\r\n";
                
            } else if($loop instanceof ClassMethod){
                $this->extra .= "function ";
                if($loop->name){
                    $this->loop($loop->name);
                }
                $this->extra .= "(";
                $this->semicolon = false;
                if($loop->params){
                    $this->loop($loop->params);
                }
                $this->extra .= "){\r\n";
                $this->semicolon = true;
                if($loop->stmts){
                    $this->loop($loop->stmts);
                }
                $this->extra .= "}\r\n";
            } else if($loop instanceof For_){
                $this->extra .= "for(";
                if($loop->init){
                    $this->loop($loop->init);
                }

                if($loop->cond){
                    $this->loop($loop->cond);
                }
                $this->extra .= ";";  
                if($loop->loop){
                    $this->loop($loop->loop);
                }
                $this->extra .= "){\r\n";

                if($loop->stmts){
                    $this->loop($loop->stmts);
                }

                $this->extra .= "}\r\n";  
                
            } else if($loop instanceof PostInc){
                if($loop->var){
                    $this->loop($loop->var);
                }
                $this->semicolon = false;
                $this->extra .= "++";
            } else if($loop instanceof Foreach_){
                $this->extra .= "for(";
                
                if($loop->keyVar){
                     $this->extra .= "let [".$loop->keyVar->name;
                     if($loop->valueVar){
                        $this->extra .= ",".$loop->valueVar->name;
                     }
                     $this->extra .= "] of Object.entries(".$loop->expr->name.")";
                } else {
                    if($loop->valueVar){
                        $this->extra .= $loop->valueVar->name;
                    }
                    $this->extra .= " in ".$loop->expr->name;
                }   
                
                $this->extra .= "){\r\n";

                if($loop->stmts){
                    $this->loop($loop->stmts);
                }

                $this->extra .= "}";  
            } else if($loop instanceof Echo_){
                $this->extra .= "console.log(";
                if($loop->exprs){
                    $this->loop($loop->exprs);
                }
                $this->extra .= ");\r\n";  
            }

            $this->index ++;
            

        } 
        
    }

    public function get_function($method,$class=null){

        if (!empty($class)) $func = new ReflectionMethod($class, $method);
        else $func = new ReflectionFunction($method);
    
        $f = $func->getFileName();
        $start_line = $func->getStartLine();
        $end_line = $func->getEndLine() - 1;
        $length = $end_line - $start_line;
    
        $source = file($f);
        //dd($source);
        // $source = implode('', array_slice($source, 0, count($source)));
        // $source = preg_split("/".PHP_EOL."/", $source);
        
        $body = '';
        for($i=$start_line; $i<$end_line; $i++){
            if(isset($source[$i])){
                $body.="{$source[$i]}\n";
            }
            
        }
            
        
        return($body);   
    }



    public function raw($fun,$ignore = ["raw(function(){",");"])
    {   
      
        $code = "<?php ";
        if(is_string($fun)){
            $code .= $fun;
        } else {
            $str = $this->get_function($fun);
            $code .= $str;
        }

        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        try {
            $ast = $parser->parse($code);
        } catch (Error $error) {  
            throw new Exception("Invalid code format : ".$code);
            return;
        }
        //dd($ast);
        $this->loop($ast);
    }

    public function rawJs($raw)
    {   
        
        $this->extra .= $raw;
    }

    public static function ajax($type,$url,$options = [])
    {   
        $js = "var xhttp = new XMLHttpRequest();\r\n";
        $js .= "xhttp.open('".$type."', '".$url."', true);\r\n";
        if(isset($options['header'])){
            foreach ($options['header'] as $key => $value) {
                $js .= "xhttp.setRequestHeader('".$key."', '".$value."');\r\n";
            }
        }
        $query = "";

        if(isset($options['onload'])){
            $query .= "xhttp.onload = function() {\r\n";
            $query .= $options['onload'];
            $query .= "\r\n};\r\n";
        }
        
        if(isset($options['data'])){
            if(is_array($options['data'])){
                foreach ($options['data'] as $key => $value) {
                    $query .= $key."=".$value;
                }
                $js .= "xhttp.send('".$query."');\r\n";
            }
        } else {
            $js .= "xhttp.send();\r\n";
        }

       
        return $js;
    }

    public function fetch($url,$options = [],$success = null)
    {   
        if(!isset($options['functionName'])){
            $functionName = "getfetch";
        } else {
            $functionName = $options['functionName'];
            unset($options['functionName']);
        }
        $matches = [];
        preg_match_all("/\\{(.*?)\\}/", $url, $matches); 
        if(isset($matches[1][0])){
            $arr = explode("=" , $matches[1][0]);
            $url = str_replace($matches[0][0], "", $url);
            $f = "var $functionName = function(".$arr[0]." = `".$arr[1]."`) {
                return fetch('$url'+".$arr[0].",".json_encode($options).")
                .then(response => response.json())
                .then(data => {
                    return data;
                });
            };\r\n";
        } else {
            $f = "var $functionName = function() {
                return fetch('$url',".json_encode($options).")
                .then(response => response.json())
                .then(data => {
                    return data;
                });
            };\r\n";
        }
        
        $this->extra = $f.$this->extra;
        if($success){
            $this->extra .= "getfetch().then(data => {\r\n";
                $success($this);
            $this->extra .= "}).catch(err => console.log(err));\r\n";
        }
    }

    function result($clean = true){
        return $clean ? $this->cleanScript($this->extra): $this->extra;
    }


}

class JsArray {
    public function __construct($arr) {
        
    }
}