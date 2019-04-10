@component('mail::layout')
@slot('header')
   {{--Empty header--}}
@endslot
<table>
   <tr>
      <td colspan="3"><hr/></td>
   </tr>
   <tr>
      <td colspan="3"><b>@lang('mail.today')</b></td>
   </tr>
   <tr>
      <td>
         <table cellspacing="25">
            <tr>
               <td>
                  <table>
                     <tr>
                        <td colspan="2"><b>@lang('mail.orders')</b></td>
                     </tr>
                     <tr>
                        <td>Pending</td>
                        <td>{{ $today['orders']['PENDING'] }}</td>
                     </tr>
                     <tr>
                        <td>InProgress</td>
                        <td>{{ $today['orders']['INPROGRESS'] }}</td>
                     </tr>
                     <tr>
                        <td>Completed</td>
                        <td>{{ $today['orders']['COMPLETED'] }}</td>
                     </tr>
                     <tr>
                        <td>Cancelled</td>
                        <td>{{ $today['orders']['CANCELLED'] }}</td>
                     </tr>
                  </table>
               </td>
               <td>
                  <table>
                     <tr>
                        <td colspan="2"><b>@lang('mail.tickets')</b></td>
                     </tr>
                     <tr>
                        <td>Opened</td>
                        <td>{{ $today['tickets']['OPEN'] }}</td>
                     </tr>
                     <tr>
                        <td>Closed</td>
                        <td>{{ $today['tickets']['CLOSED'] }}</td>
                     </tr>
                     <tr>
                        <td>&nbsp;</td>
                     </tr>
                     <tr>
                        <td>&nbsp;</td>
                     </tr>
                  </table>
               </td>
               <td>
                  <table>
                     <tr>
                        <td colspan="2"><b>@lang('mail.users')</b></td>
                     </tr>
                     <tr>
                        <td>@lang('mail.new')</td>
                        <td>{{ $today['users']['new'] }}</td>
                     </tr>
                     <tr>
                        <td>&nbsp;</td>
                     </tr>
                     <tr>
                        <td>&nbsp;</td>
                     </tr>
                     <tr>
                        <td>&nbsp;</td>
                     </tr>
                  </table>
               </td>
            </tr>
         </table>
      </td>
   </tr>
   <tr>
      <td colspan="3"><hr/></td>
   </tr>
   <tr>
      <td colspan="3"><b>@lang('mail.this_month')</b></td>
   </tr>
   <tr>
      <td>
         <table cellpadding="20">
            <tr>
               <td>
                  <table>
                     <tr>
                        <td colspan="2"><b>@lang('mail.orders')</b></td>
                     </tr>
                     <tr>
                        <td>Pending</td>
                        <td>{{ $month['orders']['PENDING'] }}</td>
                     </tr>
                     <tr>
                        <td>InProgress</td>
                        <td>{{ $month['orders']['INPROGRESS'] }}</td>
                     </tr>
                     <tr>
                        <td>Completed</td>
                        <td>{{ $month['orders']['COMPLETED'] }}</td>
                     </tr>
                     <tr>
                        <td>Cancelled</td>
                        <td>{{ $month['orders']['CANCELLED'] }}</td>
                     </tr>
                  </table>
               </td>
               <td>
                  <table>
                     <tr>
                        <td colspan="2"><b>@lang('mail.tickets')</b></td>
                     </tr>
                     <tr>
                        <td>Opened</td>
                        <td>{{ $month['tickets']['OPEN'] }}</td>
                     </tr>
                     <tr>
                        <td>Closed</td>
                        <td>{{ $month['tickets']['CLOSED'] }}</td>
                     </tr>
                     <tr>
                        <td>&nbsp;</td>
                     </tr>
                     <tr>
                        <td>&nbsp;</td>
                     </tr>
                  </table>
               </td>
               <td>
                  <table>
                     <tr>
                        <td colspan="2"><b>@lang('mail.users')</b></td>
                     </tr>
                     <tr>
                        <td>@lang('new')</td>
                        <td>{{ $month['users']['new'] }}</td>
                     </tr>
                     <tr>
                        <td>&nbsp;</td>
                     </tr>
                     <tr>
                        <td>&nbsp;</td>
                     </tr>
                     <tr>
                        <td>&nbsp;</td>
                     </tr>
                  </table>
               </td>
            </tr>
         </table>
      </td>
   </tr>
   <tr>
      <td colspan="3"><hr/></td>
   </tr>
   <tr>
      <td colspan="3"><b>@lang('mail.lifetime')</b></td>
   </tr>
   <tr>
      <td>
         <table cellpadding="20">
            <tr>
               <td>
                  <table>
                     <tr>
                        <td colspan="2"><b>@lang('mail.orders')</b></td>
                     </tr>
                     <tr>
                        <td>Pending</td>
                        <td>{{ $lifetime['orders']['PENDING'] }}</td>
                     </tr>
                     <tr>
                        <td>InProgress</td>
                        <td>{{ $lifetime['orders']['INPROGRESS'] }}</td>
                     </tr>
                     <tr>
                        <td>Completed</td>
                        <td>{{ $lifetime['orders']['COMPLETED'] }}</td>
                     </tr>
                     <tr>
                        <td>Cancelled</td>
                        <td>{{ $lifetime['orders']['CANCELLED'] }}</td>
                     </tr>
                  </table>
               </td>
               <td>
                  <table>
                     <tr>
                        <td colspan="2"><b>@lang('mail.tickets')</b></td>
                     </tr>
                     <tr>
                        <td>Opened</td>
                        <td>{{ $lifetime['tickets']['OPEN'] }}</td>
                     </tr>
                     <tr>
                        <td>Closed</td>
                        <td>{{ $lifetime['tickets']['CLOSED'] }}</td>
                     </tr>
                     <tr>
                        <td>&nbsp;</td>
                     </tr>
                     <tr>
                        <td>&nbsp;</td>
                     </tr>
                  </table>
               </td>
               <td>
                  <table>
                     <tr>
                        <td colspan="2"><b>@Lang('mail.users')</b></td>
                     </tr>
                     <tr>
                        <td>@lang('mail.total')</td>
                        <td>{{ $lifetime['users']['total'] }}</td>
                     </tr>
                     <tr>
                        <td>&nbsp;</td>
                     </tr>
                     <tr>
                        <td>&nbsp;</td>
                     </tr>
                     <tr>
                        <td>&nbsp;</td>
                     </tr>
                  </table>
               </td>
            </tr>
         </table>
      </td>
   </tr>

</table>
@slot('footer')
    {{--Empty footer--}}
@endslot
@endcomponent