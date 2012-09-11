var cmcUsers = new Class({

    initialize:function () {
        var groups = document.id('groups');
        var overflow = new Element('div', {
            style:'top:0;left:0;width:100%;height:100%;background:#000;position:absolute;opacity:0.8;z-index:9998'
        });

        overflow.inject(document.body);

        groups.setStyle('display', 'block');

        var form = document.id('addGroup');
        form.addEvent('submit', function () {
            var usergroups = form.getElements('input[name=usergroups[]]:checked');
            if (usergroups.length) {
                return true;
            } else {
                alert('Select group please');
                return false;
            }
        });

        document.id('close').addEvent('click', function () {
            overflow.destroy();
            groups.setStyle('display', 'none');
        });
    }
});