<?php
namespace Database\Seeders;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
class DatabaseSeeder extends Seeder {
 public function run(): void {
  $items = [
   ['The Summer Social','Music','A golden-hour celebration of live music, good people and summer energy.','The Old Biscuit Mill','Cape Town',12,'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?auto=format&fit=crop&w=1200&q=85',true],
   ['Design Futures Meetup','Business','A creative gathering for curious minds and people building what comes next.','Workshop17','Cape Town',18,'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1200&q=85',true],
   ['Sunday Food Market','Food & Drink','Local makers, street food, fresh finds and a slow Sunday soundtrack.','Company’s Garden','Cape Town',22,'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=1200&q=85',true],
   ['Creative Code Night','Technology','An evening exploring playful code, experiments and digital art.','Cape Town CBD','Cape Town',28,'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1200&q=85',false],
   ['Open Air Cinema','Arts','Bring a blanket and settle in for a film under the stars.','Kirstenbosch Gardens','Cape Town',35,'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=1200&q=85',false],
   ['Run Club: City Edition','Lifestyle','An easy-paced community run followed by coffee and conversation.','Green Point Park','Cape Town',8,'https://images.unsplash.com/photo-1530549387789-4c1017266635?auto=format&fit=crop&w=1200&q=85',false]
  ];
  foreach($items as [$title,$category,$summary,$venue,$city,$days,$image,$featured]) {
   $event = Event::updateOrCreate(['slug'=>Str::slug($title)], ['title'=>$title,'slug'=>Str::slug($title),'category'=>$category,'summary'=>$summary,'description'=>$summary.' More event details will be announced soon.','venue'=>$venue,'city'=>$city,'starts_at'=>now()->addDays($days)->setTime(18,0),'cover_image'=>$image,'status'=>'published','featured'=>$featured]);
   TicketType::updateOrCreate(['event_id'=>$event->id,'name'=>'General admission'],['description'=>'Standard entry','price'=>120,'quantity'=>150,'sold'=>0]);
  }
 }
}
