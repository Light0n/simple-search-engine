<?php 

class Employee extends Person implements ISelfRank{
    //Attributes
    public $_email = "", $_phone = "", $_jobTitle = "", $_department = "";
    public $_rankScore = 0;// store the ranking score assign by selfRank method

    //Constructor
    function __construct($employeeAttributes){
        parent::__construct($employeeAttributes[FIRST_NAME],$employeeAttributes[LAST_NAME]);
        $this->_phone = $employeeAttributes[PHONE];
        $this->_jobTitle = $employeeAttributes[JOB_TITLE];
        $this->_department = $employeeAttributes[DEPARTMENT];
        $this->_email = $employeeAttributes[EMAIL];
    }

    //return predifined ratio based on name
    private function getRatio($property){
        switch ($property){
            case "_email":
                return EMAIL_RATIO;
                break;
            case "_phone":
                return PHONE_RATIO;
                break;
            case "_firstName":
                return FIRST_NAME_RATIO;
                break;
            case "_lastName":
                return LAST_NAME_RATIO;
                break;
            case "_jobTitle":
                return JOB_TITLE_RATIO;
                break;
            case "_department":
                return DEPARTMENT_RATIO;
                break;
            case "directMatch":
                return DIRECT_MATCH_RATIO;
                break;
            case "partialMatch":
                return PARTIAL_MATCH_RATIO;
                break;
            case "wordMatch":
                return WORD_MATCH_RATIO;
                break;
            default:
                return 0;
                break;
        }
    }
 /**
 * selfRank method: calculate $_rankScore based on search terms input
 * Description of selfRank method implementation
 * 
 * 4 match events type:
 *  Terms Input         Text Content        Match Event           
 *  "Engineer II"       "Engineer II"       Direct Match
 *  "Engineer II"       "Engineer III"      Partial Match
 *  "Engineer II"       "Engineer A"        Word Match
 *  "Engineer II"       "Doctor II"         Word Match
 *  "Engineer II"       "Engineering"       Partial Word Match
 *  "Engineer II"       "Doctor III"        Partial Word Match
 * 
 * 4 match events type order and predefined values:
 * Direct Match > Partial Match > Word Match > Partial Word Match
 * 256000         192000          128000       0   
 * 
 * 6 possible match event output combinations: (0: not occur; 1: occur)
 * Direct Match     Partial Match   Word Match      Partial Word Match
 *      1                  0            0                   0
 *      0                  1            0                   0
 *      0                  0            1                   0
 *      0                  0            0                   1
 *      0                  0            1                   1
 *      0                  0            0                   0
 *
 * 6 fields ranking order and predefined values: 
 * email > phone > firstName > lastName > jobTitle > department
 * 32000   16000   8000        4000       2000       1000
 * 
 * formula to calculate rank score
 * $_rankScore = Match_Event_Type_Predefined_Value + [Field_Predefined_Value] + Number_of_Matches_Occur  
 * 
 * Example: 
 * Direct Match occur 2 times at fields firstName and lastName
 * $_rankScore = 256000 + [8000 + 4000] + 2  
 * Partial Match occur 3 times at fields jobTitle and deparment
 * $_rankScore = 192000 + [2000 + 1000] + 3 
 * Word Match occur 21 times at fields firstName, lastName, jobTitle, deparment
 * $_rankScore = 64000 + [8000 + 4000 + 2000 + 1000] + 21 
 * Partial Word Match occur 31 times at fields email, firstName, lastName, jobTitle, deparment
 * $_rankScore = 0 + [64000 + 8000 + 4000 + 2000 + 1000] + 31 
 * Word Match & Partial Word Match both occur and
 * - Word Match: 15 times at fields jobTitle, department
 * - Partial Word Match: 21 times at fields firstName, lastName, jobTitle
 * $_rankScore = (64000 + [2000 + 1000] + 15) + (0 + [8000 + 4000 + 2000] + 21)
 * 
 * Explain predefined values:
 * - 6 fields predefined values which are calculate based on sum of all lower order 
 * fields and plus 1000.
 * Ex:  jobTitle = department + 1000 = 2000;
 *      lastName = jobTitle + department + 1000 = 4000;
 * so Partial Word Match occur 1 time at email field (32000 + 1) will always larger 
 * than Partial Word Match occur 1000 times across phone, firstName, lastName, 
 * jobTitle and department fields (31000 + 1000). 
 * 
 * - In order to maintain the ranking order, 1000 is used as the maximum value of matches count.
 * 
 * - From the formula, the maximum value of Partial Word Match is
 * 0 + [32000 + 16000 + 8000 + 4000 + 2000 + 1000] + 1000 = 64000
 * and 128000 is used as predifined value of Word Match event type
 * 
 * Similarly, the maximum value of Word Match is
 * 64000 + [32000 + 16000 + 8000 + 4000 + 2000 + 1000] + 1000 = 128000
 * 
 * However, Word Match and Partial Word Match events can occur at the same time
 * so Partial Match predefined value is 
 * The maximum value of Word Match + The maximum value of Partial Word Match
 *      128000      +       64000      =       192000     
 * 
 * Finally, the predefined value of Direct Match is the maximum of Partial Match
 * 192000 + [32000 + 16000 + 8000 + 4000 + 2000 + 1000] + 1000 = 256000
 * 
 *  
**/
    public function selfRank($terms){
        $employeeReflector = new ReflectionClass('Employee');
        //reset rankScore
        $this->_rankScore = 0;
        //create array contain predefined values and match count variables
        $sumRatios = array();
        $sumRatios["directMatchCount"] = 0;
        $sumRatios["partialMatchCount"] = 0;
        $sumRatios["wordMatchCount"] = 0;
        $sumRatios["partialWordMatchCount"] = 0;
        
        foreach($employeeReflector->getProperties() as $property){
            
            //if property name is _rankScore
            if($property->getName() == "_rankScore"){
                continue;//go to next property
            } 

            // get and lowercase content of current property
            $propertyContent = strtolower($property->getValue($this));
            // get name of current property
            $propertyName = $property->getName();

            //lowercase search terms
            $terms = strtolower($terms);
            
            //Check for Direct Match
            if($terms == $propertyContent){
                $sumRatios["directMatchCount"]++;//increase match count
                //get and assign predefined value of match type only one time
                if(!array_key_exists("directMatch", $sumRatios))
                    $sumRatios["directMatch"] = $this->getRatio("directMatch");
                //get and assign predefined value of match field only one 
                //time for each field
                if(!array_key_exists($propertyName, $sumRatios))
                    $sumRatios[$propertyName] = $this->getRatio($propertyName);
            }else{

                //Check for Partial Match
                $matchCount = substr_count($propertyContent, $terms);
                if($matchCount){
                    $sumRatios["partialMatchCount"] += $matchCount;//increase match count
                    //get and assign predefined value of match type only one time
                    if(!array_key_exists("partialMatch", $sumRatios))
                        $sumRatios["partialMatch"] = $this->getRatio("partialMatch");
                    //get and assign predefined value of match field only one 
                    //time for each field
                    if(!array_key_exists($propertyName, $sumRatios))
                        $sumRatios[$propertyName] = $this->getRatio($propertyName);
                }else{

                    //Check for each Word and Partial Word Match
                    foreach(explode(" ",$terms) as $term){
                        $term = trim($term);//trim unintentional white space
                        //$term is empty string
                        if($term == "")
                            continue;// go to next term

                        foreach(explode(" ", $propertyContent) as $word){
                            $word = trim($word);//trim unintentional white space
                            //$word is empty string
                            if($word == "")
                                continue;// go to next word

                            if($term == $word){//Word Match
                                $sumRatios["wordMatchCount"]++;//increase match count
                                //get and assign predefined value of match type only one time
                                if(!array_key_exists("wordMatch", $sumRatios))
                                    $sumRatios["wordMatch"] =$this-> getRatio("wordMatch");
                                //get and assign predefined value of match field only one 
                                //time for each field
                                if(!array_key_exists($propertyName, $sumRatios))
                                    $sumRatios[$propertyName] =$this-> getRatio($propertyName);

                            }else{// Check for Partial Word Match
                                $matchCount = substr_count($word, $term);
                                if($matchCount){//Partial Word Match
                                    //increase match count
                                    $sumRatios["partialWordMatchCount"] += $matchCount;
                                    //create key name for partial word match 
                                    $pwmPropertyName = 'PartialWordMatch'.$propertyName;
                                    //get and assign predefined value of match field only one 
                                    //time for each field
                                    if(!array_key_exists($pwmPropertyName, $sumRatios))
                                        $sumRatios[$pwmPropertyName] =$this-> getRatio($propertyName);
                                }
                            }
                        }
                    }
                }
            }
        }
        //if match count is out of range 
        if ($sumRatios["directMatchCount"] > MAX_MATCH_COUNT)
            $sumRatios["directMatchCount"] = MAX_MATCH_COUNT;
        if ($sumRatios["partialMatchCount"] > MAX_MATCH_COUNT)
            $sumRatios["partialMatchCount"] = MAX_MATCH_COUNT;
        if ($sumRatios["wordMatchCount"] > MAX_MATCH_COUNT)
            $sumRatios["wordMatchCount"] = MAX_MATCH_COUNT;
        if ($sumRatios["partialWordMatchCount"] > MAX_MATCH_COUNT)
            $sumRatios["partialWordMatchCount"] = MAX_MATCH_COUNT;
        //calculate rankScore
        $this->_rankScore = array_sum($sumRatios);
        }
}

?>