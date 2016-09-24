<?php

class MY_Form_validation extends CI_Form_validation{
 
    /**
     * Match one field to others
     *
     * @access  public
     * @param   string
     * @param   field
     * @return  bool
     */
    public function is_not_unique($str, $field)
    {
        sscanf($field, '%[^.].%[^.]', $table, $field);
        return isset($this->CI->db)
            ? ($this->CI->db->limit(1)->get_where($table, array($field => $str))->num_rows() != 0)
            : FALSE;
    }
    
    public function less_than_equal_to_current_year($str)
    {
        return $str <= date('Y');
    }
}