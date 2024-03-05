"use strict";

function new_user() {
    appValidateForm($('form'), {
        user: 'required',
        name: 'required',
        password: 'required',
        expiration_date: 'required'
    });
    $('#user_api').modal('show');
    $('.edit-title').addClass('hide');
    $('#user_api input[name="user"]').val('');
    $('#user_api input[name="name"]').val('');
    $('#user_api input[name="expiration_date"]').val('');
    $('input[name="password"]').val('');
    $('#password_note').addClass('hide');
    $('div[app-field-wrapper="password"]').removeClass('hide');
    $('div[app-field-wrapper="repeat_passwork"]').removeClass('hide');
}

function edit_user(invoker, id) {
    appValidateForm($('form'), {
        user: 'required',
        name: 'required',
        password: 'unrequired',
        expiration_date: 'required'
    });
    $('label[for="password"] small').remove();
    var user = $(invoker).data('user');
    var name = $(invoker).data('name');
    var expiration_date = $(invoker).data('expiration_date');
    $('#additional').append(hidden_input('id', id));
    $('#user_api input[name="user"]').val(user);
    $('#user_api input[name="name"]').val(name);
    $('#user_api input[name="expiration_date"]').val(expiration_date);
    $('input[name="password"]').val('');
    $('#password_note').removeClass('hide');
    $('#user_api').modal('show');
    $('.add-title').addClass('hide');
}