pull:
	docker pull composer:latest

start:
	docker run --rm --interactive --tty --volume $(PWD):/app composer php ./Program.php