
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="row">
        <div class="col-md-10">
            <div style="font-size:24px !important;">Master Potongan Member & Promosi Marketplace</div>
        </div>
        <div class="col-md-2">
        <button type="button" class="btn pull-right btn-success" id="btn_print" style="font-size:10pt;"  onclick="exportTableToExcelMember()">Excel</button>
        </div>
    </div>
  <!-- <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Dashboard</li>
  </ol> -->
</section>

<!-- Main content -->
<section class="content">
      <div class="nav-tabs-custom"  style="padding:0px; margin:0px;">
            <ul class="nav nav-tabs" id="tab_all_transaksi">
                <li class="active"><a href="#tab_master" onclick="javascript:changeTabMarketplace(0)" data-toggle="tab"><b>Master</b></a></li>
				<li id="header_shopee"><a href="#tab_shopee" data-toggle="tab" onclick="javascript:changeTabMarketplace(1)"><img alt="Shopee" style="width:60px;"src="data:image/png;base64,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"></a></li>
				<!--<li id="header_tiktok"><a href="#tab_tiktok" data-toggle="tab" onclick="javascript:changeTabMarketplace(2)"><img alt="Tiktok" style="width:60px;"src="data:image/png;base64,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"></a></li>-->
				<!--<li id="header_lazada"><a href="#tab_lazada" data-toggle="tab" onclick="javascript:changeTabMarketplace(3)"><img alt="Lazada" style="width:60px;"src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxEPEhUQERIWFRUVFRYXEhcXFRcWGBYYFRcXGxoaFhMaHiggGRooHxcVIjEhJik3LjAuGB8zODMtNygtLisBCgoKDg0OGhAQGjAlICUtLS0tKystLS0tMC0tKy0tLS0tLy8tLS8vLS0tLS0tLS8tKysrLS0vLS0tLy0tLS0tLf/AABEIAHMBtwMBEQACEQEDEQH/xAAcAAEAAAcBAAAAAAAAAAAAAAAAAQMEBQYHCAL/xABJEAABAwIDBAMKCwUHBQAAAAABAAIDBBEFITEGEkFhB1FxCBMiMkJyc4GRshUjMzQ1UlSTobHRFBZTYnQkQ4KSwdLwlKKzwvH/xAAbAQEAAgMBAQAAAAAAAAAAAAAAAQMCBQYEB//EADQRAAICAQEEBwcFAAMBAAAAAAABAgMRBAUSITEiM0FRcYGREzJhobHB0RQ0UuHwBiNCkv/aAAwDAQACEQMRAD8A3igCAteKY3FBl4z/AKoOnnHh+a1ut2pTpuHOXcvv3fU9mn0Vl3Hku/8ABh+J4rLOfDdZvBoyaPVx9a5XVbQu1L6T4dy5f35m8o0tdK6K49/aTsJ2ilg8F3hs6icx5rv9D+C9ei2tbR0Z9KPzXh/vQr1Oz67eMeD/ANzMzw7EYqhu9G6/WNC3tC6rT6mu+O9W8/U0N2nspliaKtXlIQBAEAQBAY7tDtdBSXYPjJfqNOTT/O7yezXkr6qJT+CNrodk3anEn0Y977fBdv0+JrbGMeqKt29LIbX8FjbtY3sHE8zmtrTTCC4I67S6CjTRxCPi3xb/AN3LgXXZ/beemsyW80fM+G3zXnXsPtCW6GFnGPB/I8et2JTf0q+jL5PxXZ5ejNj4RjEFWzfheHfWGjm+c3ULU3UTqeJo5HVaO7TS3bY4+PY/BleqTzBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEBJqqpkQ3nuAH4nsHFUajU1aeO9ZLC/3IsrqnY8RRi+KY++S7Y7sb1+UfXw9XtXKa3blt3Rp6Mfm/wAeXqbjT6CEOM+L+RYXLSmyRJcskZolOWaMkeYah8Tg9ji1w0I/5mOSvqtnXLeg8MThGcd2SyjLsG2wa6zKizTwePFPnDye3TsXTaPayn0buD7+z+voaXU7KkulTxXd2+Xf9fEypjg4Aggg5gjMHsK3KafFGnaaeGRUkBAUmJYlDTM35nho4dbj1NaMyexSlkv0+mtvlu1rL/3M13tBtrNUXZBeKPS9/jHDm4eL2DPmr661zZ1Wi2LVTiVvSl8l+fP0MSK90DeHkr1wMkeSvTEyJlLVSQvEkbyxw0c02PZzHJXuEZx3ZLKMLKoWxcJrK7mbA2d6QGutHVjdOglaPBPnt8ntGXYtTqdkNdKnj8Px/vU5fX/8flHM9NxX8Xz8n2/XxM5ika8BzSHNIuCDcEdYI1WllFxeGuJzcouL3ZLDPagxCAIAgCAIAgCAIAgCAIAgCAIAgCAIAgCAIAgCAIAgCAIAgCAxrbjbKHB445Zo5HiR5YAzduCBfPeIXo02mlfJqLIbwWfZHpTpcTqW0kUMzHOa5137m7ZoudHEq6/QTphvyaIUkzPV4TIIAgCAIAgNcS4t35xdJkScuoDgB1L51q52X2OyTzn5fA6uOl9lFRjyDl5USiU5ZoyRJcskZolOWaMkSXLNGSJL16qyxFXhuNz0vyT/AAeLHZt9nD1WW2019lXCL4d3YU36Om/31x71z/3iX+Lb9wHh04J62yWHsLT+a20NY3zRrpbCTfRs9V/ZTV238rhaKFrD1ucX+wWAuvRG3eLqdg1p5sm34LH5MQrauSdxkleXuPEn8ANAOQXogb2mqFUd2tYRTleqBcjyV6oGR4K9cDI8lemBkQK9MSTwSr1JRWWZGYdGWJyNqf2feJjexx3Scg5udwOB1HO/ILUbXUJ176XFPmaD/kOmrlp/a46Sa4/B9htRc4cUEAQBAaK7opxFRS2JHxUnvhbzZKzGXkVWFq6A3k4m65J/s0mp/mjVu1FileP5IhzOilzxcEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEBqTui/mtN6d3uFbXZPWS8CuzkYJ0G/S0fo5fcK9+0+o80Yw5nSy5suCAIAgCAIDT5Xz07kmRVDm6HLqWMoKRjKCZUx1LXciqnW0UuDRFyhEIlOWaM0SXLNGSJLl6qyxEpy2FZYiS5e+ssRKcthWWolle2BmjyV6oGSPJXqgZHgr1QMkeSvTFpczIlOk6keo7ImSRLJRSb4syMo6NvnzPMk91efaH7d+KNNt79lLxX1NwrnjgwgCAIDRHdGfOKT0UnvBbzZHuy8iqwtXQD9Ju/ppPejVu1epXj+SK+Z0YueLggCA8ySNaC5xAAFySbADmToiWQY/Nt1hbHbprqe/KVrh7RcK9aW58dx+hG8i7YbitPVN36eaOVvExva8Dtsciq5wlB4ksE5I1WJwQndlmjYSLgPe1pt12J0yPsUKEpckMnqkxCGa4iljktruPa619L2OSShKPNAVdfDDbvssce9fd33tbe1r2uc9R7UjCUuSBT/D1J9qg++j/VZeyn/F+hGUPh6k+1QffR/qnsp/xfoMofD1J9qg++j/AFT2U/4v0GUTabFaeV25HPE93BrZGuOXIG6xcJJZaJyVixB5e8NBc4gAZkk2AHMoCwT7c4Ww7rq6nvplK13tIJV60tz/APD9CN5F0wzFqeqbvU88coGpje19u2xyVc65QeJLBOT3V4lBCQ2WaOMkXAe9rSR1gE6KFCUuSBGkxGGYkRSxyEZkMe11u2xySUJR5oFSsQEBYa7bPDYHFklbA1wyLe+NJB5gE2V8dNbJZUX6EZRWYVtBR1ZIp6mGUjMhkjXOA5tBuFhOmyHvRaGUa27ov5rTend7hWx2T1kvAws5GCdBv0tH6OX3CvftPqPNGMOZ0subLggLRie1FDSu3J6uCNw1a6RocO1l7/grYUWTWYxb8iMohhm1VBVODIKuGR50a2Ru8exl7lJ0WwWZRa8hlFzqalkTd+R7WNFruc4NAvkMzkq0m3hElH8PUn2qD76P9Vn7Kf8AF+hGUavXzk7ogVIIFCT2yoI5hYuCZi4Jk4SB2ix3WitxaPDlKJRJcvVWWIlOXvrLESXLYVliJTlsKy1Esr2wM0eSvVAyR5K9UDIkPlHBZ+3S5GaRJc66jfcuZmQV0SSC9ESTKejX58zzJPdVO0OofijTbe/ZS8V9TcK584MIAgCA0T3Rnzik9FJ7wW82R7svIqsLT0A/Sbv6aT3o1btXqV4/kivmdGLni4ICz7WbRwYZTOqpzkMmNHjSPN91jeZsewAngrqKZXTUIkN4OZNsNtKzFZC6eQiO/wAXC0kRsHDLynfzHPsGS6XT6WuldFce8pcmy3YbgNZVDep6aaVt7b0cT3tv5wFlZO6uHCUkvMjDJjW12GStk3Z6WUZsLmvicQLXycBvN0uNDxUP2V0WuDXqOKK7bbap+LSQzytDZWQNikI8Vxa+R28BwuHjLrusNNp1QnFcs5Jk8mwe5x+UrPMh/ORa/a/KHn9jOsqe6Kp3yGi3GOdYVF91pNvkdbLDZMkt/L7vuLDTfwdP/Bk/yO/Rbn2kO9FeGPg6f+DJ/kd+ie0h3oYZIkjLSWuBBGoIsR6lkmnxRBsHoIYTirSASGwylxtoLAXPVmQPWtftTqPNGcOZv3afH4cNp31U58FugHjPcfFa0cSfwzJyBWhpplbNQiWt4OY9sttqzFZC6Z5bFf4uFptGwcLjy3fzHPM2sMl0un0ldK4Lj3lLk2WvDcCq6oXp6aaUXsTHE94B5uAsFdO6uHvSS8yMMmd6rsMlbIWT0sozYXNfE421tcC40uNM1jmq6LXBr1HFFx212tfixgkmaBLFF3uQjJryHuIcBwJBzGl9OoV6bTKjeS5NkuWTPO5y+Wq/Rxe85eDa/KHmZVm7K+tjp43zSuDI42lz3HQAC57exaaMXJqK5stOatv+kmqxR7o43OhpbkNiBsXjrmI8Yn6vijLW1z0ml0MKVl8Zd/4KZSyYlh2F1FSS2ngkmI1EcbnkX6w0Gy9c7IQ95peJjjJUVmDVtERJLTzwEEbr3RyR2dw3XkDPsKxjbVZwTTGGi8bRbcT4jRxUtV4ckMpc2XK72FpFnji4ZeFxGuYuaatJGq1zhya5EuWUXToN+lo/Ry+4VVtPqPNEw5nSpNsz61zZcc/dJvSpLVPdS0Ehjgad10rCQ+YjWzhm2PkMyNcjZb/RbPjFKdiy+7uKpT7jWdFRSzu73DG+R50axrnu/wArQStnKcYLMngwK6t2aroGmSajqI2AXLnwyNaO1xFgq431SeFJPzGGXh23tTLh0uG1LjM13ezDI43ezcka4tc45ubYG18xpppT+jhG5Ww4d6J3uGDEgvYYm8yvhR9CIFCTyVIIFSCCEnsSnio3TFxIk3V1YRKcvfWWIkvWwrLESnLYVlqJZXtgZokSzAcyrlYomcYtlM+QlTvuRalg8KyJkF6IkhXxAXpiSZR0a/PmeZJ7qp1/UPyNPt79lLxX1NxLQHBBAEAQGie6M+XpPRSe8FvNke7LyKrC09AP0m7+mk96NW7V6leP5Ir5nRi54uCA526ecedPXCkB+LpmgW4GSQBzjz8EsHKx610Oy6VGrf7X9Cqb44Kfob2JZiU7p6ht6eC128JJDmGn+UAXPa0cVO0dU6oqMeb+hEI5OjoYmsaGMaGtaLNa0AAAaAAaBc823xZcU2LYXBWROgqI2yRuFi1w/EHVpHAjMLKFkoS3ovDDWTljb7Zg4VWPpblzLB8Lja7o3Xte3EEOaebSuo0uo9vWpdvb4lElhmwu5x+UrPMh/ORa/a/KHn9jKs3itIWhAYT0n7dMwmDdZZ1TKD3luu6NDI8fVHAcTyBt7dHpHfLj7q5/gxlLBzdS09RX1AYwOmnnf13c5zjclxPrJJ0FyV0cnCqGXwSKebOn+j7Y2LCKcRNs6V9jUSW8dw4DjuC5AHadSVzGq1Mr55fLsRdFYNR9P2POmrG0TT4FOwFwzzlkAdc9dmFluq7utbfZVO7W5vm/ojCb44Ld0PbFsxSodLOL08G6XtzHfHuvusv9XIk+ocVZtDVOmG7HmyIRydIwQMjaGMaGtaLNa0BrWgaANGQC5xtt5ZcSMUw2GqjdBURtkjcPCa4XHaOojgRmFMJyg96Lwwct9ImyxwqtfTgkxuAkgcdTG4mwPMEObztfiuo0eo9vXvdvaUSWGZ33OXy1X6OL3nLwbX5Q8zKsuXdC48WRwUDDbvpMs2eZaw2YCOou3j2sCr2VTmTsfZwRNj7DWHR7sucVrWU1y2MAyTOGojaRe3MktaDw3r52W01eo9hW5dvJeJhFZZ1NhWGQ0kTYKeNscbfFa0WHaeJJ4k5lctOcpy3pPLLyfPC2RpY9oc1ws5rgCCDqCDkQoTaeUDm/pi2LZhlQ2Wnbann3i1uZ729vjM82xBH+IcF0Wz9U7oOMua+hTOODz0G/S0fo5fcKnafUeaEOZtXpux11Jhzo2Gz6lwhuNQwgmT2gbv8AjWq2dUrLsvs4mc3hHPez2ESV1TFSxeNK8NB4NGrnHkGgn1LobrVVBzfYVJZZ1bsvs3TYZC2CnYAABvvNt+R3Fz3cT+A0FguVuvndLeky9LBeCFSSaK6b9hYqYDEaVgY1zg2oY0WaC7xZGt8m5yI6yOsrebN1cpP2U34fgqnHtNPhbgrN5L4UfQyBUg8oCCkkgVIIICF1KJG8vVVfj3iUS3LbUyUllFiKaeZrddepbGsvhFsoJagu5BehSZfGCRKVkTMirYgK+JIV8SQvREEF6IkmU9Gvz5nmSe6qdd1D8jT7e/ZS8V9TcS0RwQQBAEBonujPl6T0UnvNW82R7svIqsLT0A/Sbv6aT3o1btXqV4/kivmdGLni4IDk7pJJ+FKze17+/wBl8vwsuq0XUQ8CiXM3L3Pwb8Gv3dTUyb/buRW/DdWn2pn2/HuRZDkbMWtMwgNFd0a1vf6QjxjHIHdgc3d/EuW82Rndl5FVhM7nH5Ss8yH85FG1+UPP7Cs3itIWlg212phwqmdUS5u0hjvYyP4NHUOJPAdeQN+noldNRXmQ3hHLWNYrUYhUOnmJfLK7QA8cmsY3qGQAXUV1wphurkihvJ0F0S7AjDIf2idoNVK3wuPeWHPcB+toXHry4XOg12r9tLdj7q+fxLYxwbCWvMzlTpUJ+Fqy/wDEHs3G2/Cy6nQft4lEuZtrueQPg+a2v7U+/wB1Db1a/itTtbrl4fdllfI2itYZhAaO7o9re+UR8rcmB7AY7fm5bvZHKfl9yuw8dzl8tV+ji95ybX5Q8yKy090CT8Jsv9ljt2b8v+t1dsrqX4/gizmXjucQ3vtYfK3IbdhdJf8AJqp2vyh5k1m8lpC0IDV/dCgfB0ROv7Uy33U1/wDnYtnsrrn4fdGFnI1z0G/S0fo5fcK2O0+o80YQ5mV90gT/AGEcP7T7fiF5dkf+/L7mVhi3QUG/CrN61xFLuedbhz3d5enamfYeaMYczpRc4XBAYl0sNacJq9/TvbSL/WEjC3/usvVos+3jjvMZcjlcLqig3iV8LPoZBAeSpBAqSSBQECpB5UkkCgJVSbMcR1L16JtXRRZV76LKSunibIKxAirUArokhXRAV8SQr4kheiIMp6Nfn7PMk91Va7qH5Gn29+yl4r6m4lozgggCAIDRPdGfL0nopPeat5sj3Z+RVYWnoB+k3f00nvRq3avUrx/JFfM6LuueLhdAc4dOuCup8RNRbwKljXtPDfYAx7e3Jjv8a6LZlqlTu9qKZriVPQhtlHQzPpKh4ZFOQWPJs1koy8I8A4WF+Ba3rJWO0tM7Iqcea+hMJY4HQ658tJNXVMhY6WV7WMYCXucQA0DiSVMYuTwgctdJW1AxWufOy/emgRwXyO40k7xHWSXHsIHBdRotP7GrdfPmyiTyzOu5x+UrPMh/OReHa/KHn9jKs3JjWKw0UL6md+5HGLuP5ADi4mwA6ytPXXKySjHmyxvByxtxtZNi1S6eS4YLtgjvlGzq5uOpPE8gAOo02njRDdXPtZTJ5ZtHoW6Pe9huJ1bfDIvSxkeKD/euH1iPFHAG+pFtXtHW73/VDl2v7GcI9puNagsCA516esEMFeKoDwKlgN/54gGOH+URn1ldDsu3eq3O77lU1xPPQptlHh876aoduw1G7Z5NmxyNvYuPBrgbE8LN4XIbR0rtipx5r6EQlg6LBvmueLiXU1DImOkkc1jGgue5xAa0DUknQKUm3hA5c6T9qhita6aO/eY2iOC9xdrSSXEHQuJJ67boOi6fRaf2NWHzfFlEnlmadzl8tV+ji95y8W1+UPMyrKzuiMEJFPXtFw28Mp6gSXR+q/fBfrI61jsm3DlW/Ffcmxdpr7oy2qGFVzZn37y9pjnsCSGOIIcB1tcGnrtcDVbDW6f21WFzXFGEXhnUlLUslY2SNzXscAWOaQWuB0II1C5dpxeGXk0lQDnXps2yjr52UtO7ehpy7eeDdskpyJaeLWgWB43dwsV0OzdK64ucub+hTOWSj6DfpaP0cvuFZ7T6jzQhzNn9O2CuqcPEzBd1NIJDlc97cN19uy7XHk0rWbMtULsPt4Gc1wNC7LY07D6uGrYLmJ9yPrNILXtvwu0uF+a319StrcH2lSeGdZYHjEFdCyop3h8bxkRqDxa4cHDiFydlcq5OMlxL08lesCTS3TvtlG5gwuBwc4uDqog3Ddw3bH517OPVujrNtzszTPPtZeX5K5y7DSYW7KjeLwQSDkRkfUvhjTTwz6GnlZR5KAghJ5KkEFIIISQKkHkqQSavxHdhXq0XXxLavfRZl06NiFYiSKtQCtiCKuiAr4kkFfEkL0RBlXRm0mubYaMkJ5ZW/MhU61/9Poabb7S0T8UbiWlODCAIAgNE90b8vSeik95q3myPdn5FVhqBbgrIJgGV9Fn0tR+l/wDVy8mu/byMo8zovbjZWLFqV1NId1wO9DJa5jeNDbiDmCOo9diOd02olRPeXmXNZRy/tHs7U4dKYKmMsdnunVjwPKY/Rw05jjY5Lp6b4XR3oMoaaKzBtucTo2COCrkawZNad2RrQODWvBDRyCws0lNjzKJKk0ScV2ixDE3NjmnlnJI3Ixexdw3YmC292C6yhRTSsxSXx/sNtjanZmbDHQx1FhJLCJXMH92HPe0NJ4u8C57bJRfG5Nx5J4IawbJ7nH5Ss8yH85Frdr8oef2M6z33RtU/epId497IkeW8C4FoDiOJAJHrPWo2RFdJ9vAmw0wCt0VF6/e7Evt9X/1Mv+5U/pqf4L0ROWP3vxL7fV/9TL/uT9NT/BeiG8zLeijaOunxWmimrKiRjjLvMfPI9ptDIRdpdY5gH1Lx6+muNEnGKT4di7zKLeTeO2mzEWK0rqaXwT40T7XMbxo4DiMyCOIJ01Wk098qZqaLWso5e2m2aqsNlMNVGWnPccM2SAeUx/EacxfMArp6b4XR3oMoaaKnBtt8Somd7p6uRrBo02e1o6mteCGjsWFmkpseZRJUmiVi+0uIYkWxzzyzXIDYxoXcLRMABd6rqa6KaeMUl8Q22R2o2Xnw3vDajKSaLvhZxjG8QA48XZX5XslGojdvbvJBrBsXucvlqv0cXvOWu2vyh5mVZufGcLirIJKadu9HI3dcPxBB4EEAg8CAtPXZKuSlHmi1rJzBt1sNU4TKQ9pfCT8VMB4LhwDvqv5HqNrhdNpdXC+PDn3FEo4LfgW1ddQC1LUyRtOe6DvMv197ddt+dlZbpqrffjkhSaJ+M7cYlWsMdRVyOYci0WY1w6nNYAHDtWNekpreYxJcmyGK7I1NJSRVlQ0xiaTcjjcLPLQ3e33DyRwAOZ1yFrq9TCyxwjxwuYccLJkHQb9LR+jl9wqjafUeaJhzOk5omvaWOAc1wIcCLggixBHELnE8PKLjmzpL6N5sMkdPA10lI43Dhdxhv5MnGw4O00vmuj0eujat2XCX1KZRwYhg2OVVE7fpZ5IifG3HEB1tN5ujtTqF7LKYWLE1kxTaLxiHSJi1QzvclbJunXc3Yye10YBt61TDRUReVH7k7zKaHZGpNDLiT2mOBm4Iy4EGUve1vgD6ouTvacBfO2T1MPaqpcW/kN3hksAXoMTrTHdloqgmRh73IcyfJcf5h18x+K+b6zZddzcocJfJm80m0504jLjH5rwMExLDpaZ27K0jqOrXea7iubv01lEt2awdFRqK7o70HkoiqS4ggIFSSQKkHkoApJJNX4juwr1aLr4llXvosq6dGyIqxAKxAirYgK6JIV0QFfEkyDZzZGorrOA73F/EcMj5jdXflzUz1Ma/izWa7a1Gk4PpS7l932fX4G1dn9naehaRE27iPDe7Nzv0HILXW3ztfSOL1u0LtXLNj4LklyRd1SeEIAgCAtuLbP0lYWuqaeKYtBDTIwOsDqBfRWQtnD3W0RhMoP3Gwv7BT/dN/RZ/qrv5v1G6h+42F/YKf7pv6J+qu/m/UbqJ1Fsjh8D2yxUcDHtN2ubG0OaesEDJRLUWyWHJ48RhF7VJJTYjh8NSwxTxMlYdWvaHC/XY8eayjOUHmLwDEZuifBnne/ZbX1DZZgPZv5epetbQ1CXvfJGO4i+4DsnQ4f8ANaZkZ03rFz7dXfHEutyuqLdRZb78skpJEzFtmaKseJKmmileGhoc9ocQ0EkC/Vcn2qIX2QWIyaDSZ7wfZ+koi401PHCX2D9xobvWva/tPtUTunZ7zyEkiGL7PUlYWuqaeOUtBDS9odYHWyQunX7rwGky3/uFhX2CD7sKz9Xd/N+o3UP3Cwr7BB92E/V3fzfqN1D9wsK+wQfdhP1d3836jdRU4fsjh9NI2aGkhjkbfde1gBFwQbHsJHrWMtRbNYlJtDCL2qSSnr6CKoYYp42SsOrXtDm+w8VlGcovMXgGIz9E+DPdvfstr6hssoHs37D1L1raGoSxvfJGO4i94BsjQYfnS0zI3ab9i59jqO+OJdbldUW6i2335ZJSSJ2L7N0VY4PqaaOVzRutL2hxAuTYcrkqIXWQWIyaDSZHCNnqOjLnU1PHEXABxY0NuBpdRO6dnvPISSLoqyTxNC2RpY9oc1ws5rgCCOog5EKU2nlAxCu6LsHmcXmka0n+G+SMepjXBo9QXqjr9RFYUjHdRW4HsFhlC4SQUrA8G4e7ekc09bXPJ3T2WWFmrusWJSJUUi7YvgtNWNa2phZK1pu0PbvAE5XCqhZODzF4DWSlwzZSgpZBNBSxRyAEBzGAEAixzWc77JrEpNoYReVSSQIvkUBimK9G+E1Lt+SjYHHUxl0V78S2MgE87L1Q1t8FhS+5i4ohhfRrhNM7fZSMc4aGRz5bdjXktB52SetvmsOX2CijIsRw2GpjMM8bZIza7HC7Tum4y5EBeeM5Qe9F4ZkWb9wsK+wQfdhXfq7v5v1I3UZIvOSSaqmZK0skaHNOoI/5Y81hZXCyO7NZRnXZKuW9B4ZhOObGvZd9Nd7dSw+MPNPlDlr2rn9XsiUelTxXd2+Xf9fE3+k2tGXRu4Pv7PPu+ngYi8EEgggjIg5EHmFpmmnhm5TTWUeUJIFAQUkhASavxHdhXq0XXx8Syr30WRdOjZEVYgFYgRViAV0SSqw7D5al4jhjL3HgOA63E5NHMq1SS4sqvvrohv2ywv8AepsvZro9ihtJVWlfwZ/dt7QfHPbly4qqeob4R4HJa/b9lvQo6K7/AP0/x9fiZuBbILznO5yRQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAWnGtn4asXcN1/B7dfX9Ydv4Lx6rQ1ahdJYfeezS663Tvo8V3P/cDXuN4BPSG7xvM4Pb4vr+qe38Vzep0NtD4rK70dJpdbVqF0Xh9z5/2WleQ9oQBASazxHdhXq0XXx8Syr30WRdOjZhZoEVYgLq1AzPZnYCaotJUXhj4Nt8Y4cgfEHbny4rLfxyNDr9u1U5hT0pd/Yvz5cPibPwvC4aVne4IwxvG2pPW52rjzKwbb5nIajU26ie/bLL/ANy7isUFAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQEHtBBBFwciDmD2hQ0msMlNp5Rh+PbFNfd9NZjtTGfFPmnyezTsWm1WyYy6VPB93Z/X+5G60m15R6N3Fd/b59/wBfEweqpnxOLJGlrhqCLf8A0c1obK51y3ZrDN/XZGyO9B5RKWBmSazxHdhXq0XXx8Syn30WNdOjZhZoF1wHZ+ornbsLPBBs57smN7XcTyFyrEePWa+jSRzY+PYlzf8Au98DauzOxdPRWefjZv4jgPB9G3ye3Xmszjdfti7VdFdGPcu3xfb9PgZMhqQgCAIAgCAIAgCAIAgCAIAgCAIAgCAIAgCAIAgCAIAgCAIAgCAIAgCAIAgCAIAgCAIAgCAIAgCAIAgCAo8TwuGpbuSsDuo6Ob5rtQqbtPXdHdmsl9GosolvQeDAMd2Rmp7vjvLHyHhtH8zRr2j2Bc9qtl2VdKHFfM6PSbUru6M+jL5eX+9TFaz5N3mleXRdfHxNvT1iLPTwPlcI42l7neK1ouT2ALp0bGc4wi5TeEu1mxdmejfSStPMQtP/AJHj8m+06K6MO85fX/8AIOcNN/8AT+y+79DYlPAyNoYxoa1os1rQAAOQGisOXnOU5OUnlvtZMQxCAIAgCAIAgCAIAgCAIAgCAIAgCAIAgCAIAgCAIAgCAIAgCAIAgCAIAgCAIAgCAIAgCAIAgCAIAgCAIAgCAIDA+krDYWQmZrA17r7xFxftAyvzXgv09SsjYlxydHsLUWStUJPKRftjsJgp6dj4o2tc9oL3aud2uOduWi9kEsGt2nqrrrpRnLKT4Ls9C/LM1wQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAEAQBAf/9k="></a></li>-->
            </ul>
            <div class="tab-content" style="padding:0px; margin:0px;">
                    <div class="tab-pane" id="tab_shopee">
                        <?php if($_GET['i'] == 1){include("v_master_promo_shopee.php");}?>
                    </div>
                    <div class="tab-pane" id="tab_tiktok">
                         <?php if($_GET['i'] == 2){include("v_master_promo_tiktok.php");}?>
                    </div>
                    <div class="tab-pane" id="tab_lazada">
                        <?php if($_GET['i'] == 3){include("v_master_promo_lazada.php");}?>
                    </div>
                    <div class="tab-pane active" id="tab_master">
                          <!-- Main row -->
                          <div class="row">
                             <div class="col-md-12">
                                <div class="box" style="border:0px; padding:0px; margin:0px;">
                                    <div class="box-header form-inline">
                                        <h3 style="font-weight:bold;">&nbsp; Potongan Membership 
                                        <span style="float:right;">&nbsp;&nbsp;&nbsp;<button onclick='simpanPromo()' class='btn btn-success'>Simpan</button></span>
                                        <span style="float:right;"><input type="number" class="form-control" id="diskonmemberall" value="'+data+'" placeholder="0" min="0" max="100" onkeyup="checkValidAll(event,'PROMO')" mouseup="checkValidAll(event,'PROMO')" onkeydown="return ( event.ctrlKey || event.altKey || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) || (95<event.keyCode && event.keyCode<106)|| (event.keyCode==8) || (event.keyCode==9) || (event.keyCode>34 && event.keyCode<40) || (event.keyCode==46) )"></input></span>
                                        <span style="float:right; margin-top:6px;">Ubah potongan, produk yang dicentang &nbsp;&nbsp;</span>
                                        </h3>
                                    </div>
                                <!-- Custom Tabs -->
                                    <div class="box-body">
                                        <table id="dataGridPromo" class="table table-bordered table-striped table-hover display nowrap" width="100%">
                                            <!-- class="table-hover"> -->
                                            <thead>
                                            <tr>
                                                <th width="35px"></th>
                                                <th width="35px"></th>
                                                <th width="50px">Kode</th>
                                                <th>Nama</th>         
                                                <th width="50px">Potongan (%)</th>  
                                                <th width="25px"><input id="checkAll" type="checkbox"></input></th>                                
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div id="tableExcelMember" style="display:none;" ></div>
                                <!-- nav-tabs-custom -->
                                </div>
                            </div>
                            <!-- /.col -->
                          </div>
                          <!-- /.row (main row) -->
                          <!--MODAL CUSTOMER KONSINYASI-->
                            <div class="modal fade" id="modal-customer-konsinyasi" >
                            	<div class="modal-dialog" style="width:70%;">
                            	<div class="modal-content">
                            		<div class="modal-body">
                            			<table id="table_customer_konsinyasi" class="table table-bordered table-striped table-hover display nowrap">
                            				<thead>
                            					<tr>
                            						<th></th>
                            						<th width="50px">Kode</th>
                            						<th>Nama</th>
                            						<th>Alamat</th>
                            						<th>Telp</th>
                            					</tr>
                            				</thead>
                            			</table>
                            		</div>
                            	</div>
                            	</div>
                            </div> 
                    </div>
        <!-- nav-tabs-custom -->
        </div>
    </div>
</section>
<!-- /.content -->
<script src="https://cdn.jsdelivr.net/npm/inputmask@5.0.7/dist/inputmask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.2/xlsx.full.min.js"></script>
<script>
function currency(amount){
   return Number(amount).toLocaleString()
}
if('<?=$_SESSION[NAMAPROGRAM]['USERNAME'] == 'USERTES'?>')
{
    $("#header_shopee").hide();
    $("#header_tiktok").hide();
    $("#header_lazada").hide();
}

const params = new URLSearchParams(window.location.search);

const index = params.get('i')??0;

if(index == 1)
{
    $('.nav-tabs a[href="#tab_shopee"]').tab('show');
}
else if(index == 2)
{
    $('.nav-tabs a[href="#tab_tiktok"]').tab('show');
}
else if(index == 3)
{
    $('.nav-tabs a[href="#tab_lazada"]').tab('show');
}

function changeTabMarketplace(index){
    
    let url = new URL(window.location.href);
    url.searchParams.set("i", index);
    
     if(index == 0)
     {
         $("#tab_grid").hide();
     }
     else if(index == 1)
     {
         $("#tab_shopee").hide();
     }
     else if(index == 2)
     {
         $("#tab_tiktok").hide();
     }
     else if(index == 3)
     {
         $("#tab_lazada").hide();
     }
    
    // Redirect to the updated URL
    window.location.href = url.toString();
}

var indexRow;
$(document).ready(function() {
    
    $('#dataGridPromo').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : false,
        'info'        : true,
        'autoWidth'   : false,
		"scrollX"	  : true,
		ajax		  : {
			url    : base_url+'Master/Data/Promo/dataGridPromo',
			dataSrc: "rows",
			dataFilter: function (data) {
                // Refresh the new table whenever DataTable reloads
                var allData = JSON.parse(data).rows; // Get all rows' data

                // Create the HTML structure for the new table
                var newTable = $('<table id="newTable" class="table table-bordered">');
                var thead = $('<thead>').append('<tr><th>Kode Customer</th><th>Nama Customer</th><th>Potongan %</th></tr>');
                var tbody = $('<tbody>');
                 // Loop through the DataTable data and create rows for the new table
         
                allData.forEach(function (row, index) {
                    var tr = $('<tr>');
                    tr.append('<td>' + (row.KODECUSTOMER) + '</td>');
                    tr.append('<td>' + (row.NAMACUSTOMER) + '</td>');
                    tr.append('<td>' + (row.DISKONMEMBER == null?"":row.DISKONMEMBER) + '</td>');;
            
                    // Append the row to the tbody
                    tbody.append(tr);
                });
            
                // Append the thead and tbody to the new table
                newTable.append(thead).append(tbody);
                // Append the new table to the DOM (you can specify where you want the new table to appear)
                $('#tableExcelMember').html(newTable); 
                
                return data;
            }
		},
        columns:[
            {data: 'IDCUSTOMER', visible:false},
            {data: 'DISKONMEMBER', visible:false},
            {data: 'KODECUSTOMER'},
            {data: 'NAMACUSTOMER'}, 
            {data: 'DISKONMEMBER', className:"text-center"},  
            {data: '', className:"text-center"},       
        ],
		columnDefs: [
		    {
                "targets": -1,
                "render" :function (data) 
                {
                    return '<input type="checkbox" class="flat-blue"></input>';
                },	
			},
			{
                "targets": -2,
                "render" :function (data) 
                {
                    
                    return '<input type="number" class="form-control diskonmember" value="'+data+'" placeholder="0" min="0" max="100" onkeyup="checkValidPromo(event)" mouseup="checkValidPromo(event)" onkeydown="return ( event.ctrlKey || event.altKey || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) || (95<event.keyCode && event.keyCode<106)|| (event.keyCode==8) || (event.keyCode==9) || (event.keyCode>34 && event.keyCode<40) || (event.keyCode==46) )"></input>';
                },	
			},
		]
    });
    
    $("#checkAll").click(function(){
        $('#dataGridPromo tbody input[type="checkbox"]').each(function() {
            
            var checkbox = $(this);
            
            checkbox.prop('checked', $("#checkAll").prop('checked'));
    
            var numberInput = checkbox.closest('tr').find('input[type="number"]');
        
            if (checkbox.prop('checked')) {
                numberInput.val($("#diskonmemberall").val());  
            }
        });
    });
    
    $('#dataGridPromo tbody').on('click', 'input[type="checkbox"]', function () {
	    var checkbox = $(this);
    
        var numberInput = checkbox.closest('tr').find('input[type="number"]');

        if (checkbox.prop('checked')) {
            numberInput.val($("#diskonmemberall").val());  
        }

	});

});

function exportTableToExcelMember() {
  var wb = XLSX.utils.table_to_book(document.getElementById('tableExcelMember'), {sheet:"Sheet 1"});
  // Access the worksheet (first sheet)
  const ws = wb.Sheets[wb.SheetNames[0]];

  // Set column widths - specify column widths for each column in the 'cols' array
  ws['!cols'] = [
    { wpx: 70 }, // Column A width in pixels
    { wpx: 200 }, // Column B width in pixels
    { wpx: 50 },  // Column C width in pixels
  ];
  // Trigger download
  XLSX.writeFile(wb, 'PROMO_MEMBER_'+dateNowFormatExcel()+'.xlsx');
}

function checkValidAll(data,jenis) {
    var inputElement = event.target;
    const classList = inputElement.classList;
    const inputValue = inputElement.value;
    
    if(inputValue < 0)
    {
        inputElement.value = 0;
    }
    if(inputValue > 100)
    {
        inputElement.value = 100;
    }
    if(jenis == "PROMO")
    {
        $('#dataGridPromo tbody input[type="checkbox"]').each(function() {
            
            var checkbox = $(this);
        
            var numberInput = checkbox.closest('tr').find('input[type="number"]');
        
            if (checkbox.prop('checked')) {
                numberInput.val(inputElement.value);  
            }
        });
    }
    else
    {
       
    }
}

function checkValidPromo(data) {
    var inputElement = event.target;
    const classList = inputElement.classList;
    const inputValue = inputElement.value;
    
    if(inputValue < 0)
    {
        inputElement.value = 0;
    }
    if(inputValue > 100)
    {
        inputElement.value = 100;
    }
}

function checkValidKonsinyasi(data) {
    var inputElement = event.target;
    const classList = inputElement.classList;
    const inputValue = inputElement.value;
    
    if(inputValue < 0)
    {
        inputElement.value = 0;
    }
}

function simpanPromo() {
    var table = $('#dataGridPromo').DataTable();
    
     var allData = [];

     // Loop through each row of the DataTable
     table.rows().every(function() {
         var row = this.node();
         var rowData = {}; 
         var dataRow = this.data();
         rowData.id = dataRow.IDCUSTOMER;
         rowData.diskonmemberlama = dataRow.DISKONMEMBER;
         // Get number input value
         var numberInput = $(row).find('input[type="number"]');
         rowData.diskonmemberbaru = numberInput.val(); // Get the value of the number input

         // Push the row data object into the allData array
         if(rowData.diskonmemberlama != rowData.diskonmemberbaru)
         {
            allData.push(rowData);
         }
     });

     // Log the collected data or process it further
	// var isValid = $('#form_input').form('validate');
	if (1) {
		mode = $('[name=mode]').val();
        
        $.ajax({
            type      : 'POST',
            url       : base_url+'Master/Data/Promo/simpanPromo',
            data      : {'data' : JSON.stringify(allData)},
            dataType  : 'json',
            beforeSend: function (){
                //$.messager.progress();
            },
            success: function(msg){
                if (msg.success) {
                    Swal.fire({
                        title            : 'Simpan Data Sukses',
                        type             : 'success',
                        showConfirmButton: false,
                        timer            : 1500
                    });
                    $("#dataGridPromo").DataTable().ajax.reload();
                } else {
                    Swal.fire({
                        title            : msg.errorMsg,
                        type             : 'error',
                        showConfirmButton: false,
                        timer            : 1500
                    });
                }
            }
        });
	}
}

function get_akses_user(kodemenu, callback) {
	$.ajax({
		dataType: "json",
		type: 'POST',
		url: base_url+"Master/Data/User/getUserAkses",
		data: "kodemenu=" + kodemenu+ " &iduser=<?= $_SESSION[NAMAPROGRAM]['IDUSER']?>",
		cache: false,
		success: function (msg) {
			if (msg.success) {
				callback(msg.data);
			} else {
				$.messager.alert('Error', msg.errorMsg, 'error');
			}
		}
	});
}
</script>
