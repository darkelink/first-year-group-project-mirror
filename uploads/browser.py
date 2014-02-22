#!/usr/bin/env python

import urllib
import sys

#########################################
#TAGS
#########################################
def html(args):
  while tokens[tagIndex+1] != "</html>":
    printNextTag()

def head(args):
  sys.stdout.write("\033[41m")
  while tokens[tagIndex+1] != "</head>":
    printNextTag()
  sys.stdout.write("\033[0m")
  incTagIndex()

def title(args):
  sys.stdout.write("\033]2;")
  while tokens[tagIndex+1] != "</title>":
    printNextTag()
  sys.stdout.write("\007")
  incTagIndex()

def body(args):
  while tokens[tagIndex+1] != "</body>":
    printNextTag()
  incTagIndex()

def h1(args):
  print "\033[33mHEADING 1:\033[0m",
  while tokens[tagIndex+1] != "</h1>":
    printNextTag()
  print
  print
  incTagIndex()

def p(args):
  print "\033[33mPARAGRAPH:\033[0m",
  while tokens[tagIndex+1] != "</p>":
    printNextTag()
  print
  incTagIndex()

def em(args):
  sys.stdout.write("\033[1m ")
  while tokens[tagIndex+1] != "</em>":
    printNextTag()
  sys.stdout.write("\033[0m")
  incTagIndex()

def a(args):
  global links
  global isLinks
  sys.stdout.write(" \033[4m");
  while tokens[tagIndex+1] != "</a>":
    printNextTag()
  sys.stdout.write("\033[0m{" + str(len(links)) + "} ");
  links.append(args[0][args[0].find("href=") + 6:-1])
  incTagIndex()

tags = {"html" : html,
        "head" : head,
        "title": title,
        "body" : body,
        "h1"   : h1,
        "p"    : p,
        "em"   : em,
        "a"    : a,
}

##################################
#COMMANDS
##################################
def quit():
  print "Exiting..."
  sys.exit(0)

def followLink():
  global links
  if len(links) == 0:
    print "No links found on current page"
    return
  else:
    parseLinks()
    try:
      link = int(getInput("Enter link index"))
    except ValueError:
      print "Invalid selection"
      return
    try:
      gotoPage(links[link])
    except IndexError:
      print "Invalid selection"

def go():
  url = getInput ("Enter url")
  if url[:4] != "http":
    url = "http://" + url
  print "Loading page: " + url
  try:
    gotoPage(url)
  except IOError:
    print "Couldn't load page"

def quickLinks():
  print "\033[1mQuick Links:\033[0m"
  i = 0
  for link in qLinks:
    print "\033[31m" + str(i) + "\033[0m:",
    print link
    i += 1
  try:
    pageNumber = int(getInput("Enter page number"))
  except ValueError:
    print "Invalid selection"
    return
  try:
    gotoPage(qLinks[int(pageNumber)])
  except IndexError:
    print "Invalid selection"

def refresh():
  gotoPage(url)

def showHelp():
  print "\033[31mf\033[0m: Go to quick links"
  print "\033[31mg\033[0m: Go to url"
  print "\033[31mh\033[0m: Show help"
  print "\033[31ml\033[0m: Navigate link on current page"
  print "\033[31mq\033[0m: Quit"
  print "\033[31mr\033[0m: Reload the current page"
  
commands = {'q' : quit,
            'l' : followLink,
            'g' : go,
            'f' : quickLinks,
            'r' : refresh,
            'h' : showHelp,
}


#####################################
#THE REST
#####################################
def getInput(message):
  myInput = raw_input("\033[36m" + message + " > \033[0m")
  if myInput == "":
    return getInput(message)
  else:
    return myInput

def getCommand():
  while True:
    command = getInput("Enter command (h for help)")[0]
    if command in commands:
      commands[command]()
    else:
      print command + " is not a valid command."

def gotoPage(page):
  global tokens
  global tagIndex
  global links
  global url
  data = urllib.urlopen(page)
  tokens = data.read().split()
  tagIndex = -1
  links = []
  print "\033[2J"
# while tokens[tagIndex+1] != "<html>"
#   incTagIndex()
  printNextTag()
  print
  url = page

def printNextTag():
  incTagIndex()
  if tokens[tagIndex][0] == '<':
    args = []
    func = tokens[tagIndex].strip('<>')
    while tokens[tagIndex][-1] != '>':
      incTagIndex()
      args.append(tokens[tagIndex].strip('<>'))
    if func in tags:
      tags[func](args)
    else:
      print "\033[1m Tag: " + func + " not supported\033[0m"
  else:
    print tokens[tagIndex],

def parseLinks():
  global links
  global url
  print "\033[1mLINKS:\033[0m"
  i = 0
  for link in links:
    print "\033[31m" + str(i) + "\033[0m:",
    if link[0] == '.':
      link = url[0:url.rfind('/')] + (link[1:])
      links[i] = link
    print link
    i += 1

def incTagIndex():
  global tagIndex
  tagIndex += 1

###################################
#PROGRAM ENTRY
###################################
qLinks = ["http://studentnet.cs.manchester.ac.uk/ugt/COMP18112/page1.html",
          "http://studentnet.cs.manchester.ac.uk/ugt/COMP18112/page2.html",
          "http://studentnet.cs.manchester.ac.uk/ugt/COMP18112/page3.html"]

url = "http://studentnet.cs.manchester.ac.uk/ugt/COMP18112/page1.html"
gotoPage(url)
getCommand()

