require './bootstrap.rb'

feature 'Login Page' do
  it 'shows the login page content' do
    Login::visit

    page.should have_content('Username')
    page.should have_content('Password')
    page.should have_content('I forgot my password')
  end

  it 'rejects when submitting no credentials' do
    Login::login('', '')

    page.should have_content('The username field is required')
  end

  it 'rejects when submitting no password' do
    Login::login('admin', '')

    page.should have_content('The password field is required')
  end

  it 'logs in when submitting valid credentials' do
    Login::login

    page.should have_content('CP Home')
  end

  it 'rejects when submitting invalid credentials' do
    Login::login('noone', 'nowhere')

    page.should have_content('That is the wrong username or password')
  end

  it 'locks the user out after four login attempts' do
    Login::login('noone', 'nowhere')
    Login::login('noone', 'nowhere')
    Login::login('noone', 'nowhere')
    Login::login('noone', 'nowhere')

    page.should have_content('You are only permitted to make four login attempts every 1 minute(s)')

    Login::button.value.should eq 'Locked'
  end

  it 'shows the reset password form when link is clicked' do
    Login::visit

    click_link 'I forgot my password'

    page.should have_content('Reset Password')
  end
end
