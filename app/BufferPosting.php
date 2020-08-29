<?php

namespace Bulkly;

use Illuminate\Database\Eloquent\Model;

class BufferPosting extends Model
{
   public function groupInfo()
    {
        return $this->hasOne(SocialPostGroups::Class, 'id', 'group_id');
    }
   public function accountInfo()
    {
        return $this->hasOne(SocialAccounts::Class, 'id', 'account_id');
    }

//    belongs to relation by rubel
    public function group(){
       return $this->belongsTo(SocialPostGroups::class,'group_id','id');
    }
    public function account(){
        return $this->belongsTo(SocialAccounts::class,'account_id','id');
    }

}
