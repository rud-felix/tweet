# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = "2"

box      	         = 'ubuntu/trusty64'
url      	         = 'https://cloud-images.ubuntu.com/vagrant/trusty/current/trusty-server-cloudimg-amd64-vagrant-disk1.box'
ip       	         = '192.168.33.10'
ram      	         = '1024'
cpus     	         = '2'
ssh_username             = 'vagrant'
ssh_password		 = 'vagrant'

common_provision_script  = "./vagrant.d/provision.sh"
lemp_provision_script	 = "./vagrant.d/LEMP/provision.sh"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = box
  config.vm.box_url = url
  config.vm.network "private_network", ip: ip
  config.vm.network "forwarded_port", guest: 80, host: 8081
  # config.vm.synced_folder ".", "/vagrant", :mount_options => [ "dmode=775", "fmode=755" ]
  config.vm.synced_folder ".", "/vagrant", id: "application", :nfs => true
  config.vm.provision "shell", path: lemp_provision_script
  config.vm.provision "shell", path: common_provision_script
  config.vm.provider "virtualbox" do |v|
    v.customize ["modifyvm", :id, "--memory", ram, "--cpus", cpus]
  end

  config.ssh.username = ssh_username
  config.ssh.password = ssh_password
  config.ssh.insert_key = 'true'
  config.ssh.forward_agent = true

  if Vagrant.has_plugin?("vagrant-cachier")
      config.cache.scope = :machine

      config.cache.synced_folder_opts = {
        type: :nfs,
        mount_options: ['rw', 'vers=3', 'tcp', 'nolock']
      }

      config.cache.enable :generic, {
        "cache"  => { cache_dir: "/var/www/app/cache" },
        "logs"   => { cache_dir: "/var/www/app/logs" },
        "vendor" => { cache_dir: "/var/www/vendor" },
      }
  end
end
