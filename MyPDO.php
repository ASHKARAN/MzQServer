<?PHP
namespace MzQ;
use \PDO;
use MzQ\Core\Errors;
use PDOException;

class MyPDO extends  PDO {


    public function __construct() {


        //ساخت شی جدید از دیتابیس
        try {
            //اطلاعات اتصال به دیتابیس
             parent::__construct("mysql:host=" . Config::servername . ";dbname=" .
                     Config::dbname, Config::username, Config::password );

             self::$instance = $this;//ذخیره شی در شی ثابت
             self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//نمایش ارور ها
             self::$instance->exec("set names utf8");//اتصال برای نمایش کاراکتر فارسی
          } catch (PDOException $e) {
                new  Errors($e) ;
          }
    }

    private function __clone()
    {
    }

    public static function getInstance(){


        if (self::$instance==null) { // اگر شی قبلی از دیتابیس وجود نداشت
             try {
              // یک شی جدید از دیتابیس می سازیم
              self::$instance =  new MyPDO("mysql:host=" . Config::servername . ";dbname=" . Config::dbname, Config::username, Config::password);
              self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//نمایش ارور ها
              self::$instance->exec("set names utf8");//اتصال برای نمایش کاراکتر فارسی
               } catch (PDOException $e) {
                 new  Errors($e) ;
          }
             }

         return self::$instance;//بازگرداندن شی به خروجی
    }
    // این شی درطول دوره حیات برنامه رابط ما و دیتابیس خواهد بود
    static private $instance = null;

    public static function getRowCount($stmt)
    {
        //نمایش تعداد سطرهایی که در جواب دیتابیس برگرداند
        return $stmt->rowCount() ;
    }


    public static function getError($stmt)
    {
        //دریافت ارور دیتابیس
        return $stmt->errorInfo() ;
    }

    public static function getLastInsertId($conn) {
        return  $conn->lastInsertId();
     }


   /**
    *
    * @param String $sql SQL Query
    * @param Array $values array to bind with sql query
    * @param Boolean $autoErroResponder automatically send json response on error
    * @param Boolean $fetchAll fetch all items
    * @param Integer $fetchStyle
    * @return Array or \PDOException
    */
   public static  function doSelect($sql, $values = array(), $autoErroResponder = false , $fetchAll = true, $fetchStyle = PDO::FETCH_ASSOC)
    {

        $conn = MyPDO::getInstance();
        $stmt =  $conn->prepare($sql);
        $result = null;
        if($values != NULL) {
            foreach ($values as $key => $value) {
                $stmt->bindValue($key + 1, $value);
            }
        }
        try {
            $stmt->execute();
            if ($fetchAll) {
                $result = $stmt->fetchAll($fetchStyle);
            } else {
                $result = $stmt->fetch($fetchStyle);
            }
            return $result;
        } catch (\PDOException $ex) {
            if($autoErroResponder)  new  Errors("Internal Server Error" ) ;
            return $ex;
        }
    }


    /**
     *
     * @param String $sql SQL Query
     * @param Array $values array to bind with sql query
     * @param Boolean $autoErroResponder automatically send json response on error
     * @return Ineteger or \PDOException
     */
    public static  function doQuery($sql, $values = [] ,  $autoErroResponder = false )
    {
        $stmt = self::getInstance()->prepare($sql);
        foreach ($values as $key => $value) {

            $stmt->bindValue($key + 1, $value);
        }
        try {
             $stmt->execute();
        } catch (\PDOException $ex) {
            if($autoErroResponder)  new Errors("Internal Server Error") ;
            return $ex;
        }


        return $stmt->rowCount();


    }







}
