Vagrant.require_version ">= 1.5"

Vagrant.configure("2") do |config|
	config.vm.box = "debian/jessie64"

	config.vm.provider :virtualbox do |vb|
		vb.name = "Inachis.DEV"
		vb.customize [
            "modifyvm", :id,
            "--name", "default",
            "--memory", 2048,
            "--natdnshostresolver1", "on",
            "--cpus", 1,
        ]
	end
	config.vm.hostname = "inachis.dev"
	config.vm.network :private_network, ip: "192.168.33.99"
	config.ssh.forward_agent = true
	config.ssh.insert_key = true

	config.vm.provision "ansible" do |ansible|
		ansible.playbook = "build/ansible/playbook.yml"
		ansible.sudo = true
	end

	config.vm.synced_folder "./", "/vagrant", type: "nfs"
end
