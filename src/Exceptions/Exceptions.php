<?php

namespace Latex2MathML\Exceptions;

use Exception;

class NumeratorNotFoundError extends Exception {}
class DenominatorNotFoundError extends Exception {}
class ExtraLeftOrMissingRightError extends Exception {}
class MissingSuperScriptOrSubscriptError extends Exception {}
class DoubleSubscriptsError extends Exception {}
class DoubleSuperscriptsError extends Exception {}
class NoAvailableTokensError extends Exception {}
class InvalidStyleForGenfracError extends Exception {}
class MissingEndError extends Exception {}
class InvalidAlignmentError extends Exception {}
class InvalidWidthError extends Exception {}
class LimitsMustFollowMathOperatorError extends Exception {}
