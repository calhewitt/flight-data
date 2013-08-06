require 'open-uri'
require 'nokogiri'
require 'json'
require 'rest-client'

document = open("http://www.ourairports.com/airports/KLAX/routes/")
doc =  Nokogiri::HTML(document, nil, 'UTF-8')

doc.css('dt').each do |link|
  city = link.children[2].content[2..-4]
  
  address = JSON.parse(RestClient.get("maps.googleapis.com/maps/api/geocode/json?sensor=false&address=#{URI.encode city}"))
  puts address["results"][0]["geometry"]["location"]["lat"] 
  puts ","
  puts address["results"][0]["geometry"]["location"]["lng"]   
  puts ";"
  sleep 0.5
end